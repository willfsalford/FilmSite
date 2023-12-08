<?php

namespace App\Controller;


use App\Entity\Film;
use App\Form\DeleteFilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Homepage extends AbstractController
{
    public function homepage(EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $repository = $entityManager->getRepository(Film::class);
        $allFilms = $repository->findALL();

        return $this->render('Home/home.html.twig', [
            'allFilms' => $allFilms,
            'error' => $authenticationUtils->getLastAuthenticationError(),
            //'delForm' => $form
        ]);
    }

    #[Route('/{id}/delete', name: 'filmDelete')]
    #[IsGranted('ROLE_MOD')]
    public function delFIlm($id, EntityManagerInterface $entityManager)
    {
        $filmRepository = $entityManager->getRepository(Film::class);

        $delReview = $filmRepository->findByIDField($id);

        $entityManager->remove($delReview[0]);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');


    }

}