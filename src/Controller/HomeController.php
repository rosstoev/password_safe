<?php

declare(strict_types=1);

namespace App\Controller;


use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render("pages/home.html.twig");
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $userRepository->save($formData);
            return $this->redirectToRoute('home');

        }

        return $this->render('pages/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}