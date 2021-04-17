<?php

declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function register(): Response
    {
        return $this->render('pages/register.html.twig');
    }
}