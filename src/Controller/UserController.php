<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        /** @var User $user */
        $user = $this->getUser();
        if($user->isGoogleAuthenticate() == false){
            return $this->redirectToRoute('login_level_two');
        }
        dd($this->getUser());

    }
}