<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Review;
use App\Form\FilmUploadFormType;
use App\Form\ReviewDeleteType;
use App\Form\ReviewUploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FilmPage extends AbstractController
{
    public function filmpage(string $slug, EntityManagerInterface $entityManager, Request $request): Response
    {
        $repository = $entityManager->getRepository(Film::class);
        $film = $repository->findByExampleField($slug);
        $revRepository = $entityManager->getRepository(Review::class);
        $reviews = $revRepository->findByFilmField($slug);

        $review = new Review();
        $form = $this->createForm(ReviewUploadType::class, $review);
        $form->handleRequest($request);

        if (!empty($reviews))
        {
            $averageReview = $revRepository->averageFilmScore($slug);
        } else {
            $averageReview = 0;
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('homepage');
        }

        return $this->render('Film/film.html.twig',[
            'film' => $film,
            'form' => $form->createView(),
            'reviews' => $reviews,
            'averageReview' => $averageReview,
            ]);
    }

    #[Route('/film_page/{slug}/{id}/edit', name: 'revEdit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function editReview($slug, Review $review, EntityManagerInterface $entityManager, Request $request)
    {
        $repository = $entityManager->getRepository(Film::class);
        $revRepository = $entityManager->getRepository(Review::class);

        $film = $repository->findByExampleField($slug);
        $averageReview = $revRepository->averageFilmScore($slug);
        $reviews = $revRepository->findByFilmField($slug);

        $form = $this->createForm(ReviewUploadType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {#
            $review = $form->getData();

            $entityManager->persist($review);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('/');
        }

        return $this->render('Film/film.html.twig',[
            'film' => $film,
            'reviews' => $reviews,
            'averageReview' => $averageReview,
            'form' => $form->createView(),
        ]);

    }

    #[Route('/film_page/{slug}/{id}/delete', name: 'revDelete')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function revDelete(EntityManagerInterface $entityManager, $id)
    {
        $revRepository = $entityManager->getRepository(Review::class);

        $delReview = $revRepository->findByIDField($id);

        $entityManager->remove($delReview[0]);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

}