<?php

namespace App\Controller;

use App\Form\ModType;
use App\Services\GetUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Mod extends AbstractController
{
    public function mod(GetUser $getUser, Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $getUser->getUser();
        $currentRoles = $currentUser->getRoles();

        $form = $this->createForm(ModType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted('ROLE_MOD'))
            {
                $roles[] = 'ROLE_MOD';
                $currentUser->setRoles($roles);
                $entityManager->persist($currentUser);
                $entityManager->flush();
            }
        }
        return $this->render('Mod/mod.html.twig', [
            'roles' => $currentRoles,
            'form' => $form
        ]);
    }

}