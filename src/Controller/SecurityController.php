<?php

declare(strict_types=1);

namespace App\Controller;


use App\DTO\LoginTwoDTO;
use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\LoginLevelTwoFormType;
use App\Repository\UserRepository;
use App\Security\GoogleAuthenticator as SecurityGoogleAuth;
use Google\Authenticator\GoogleQrUrl;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginFormType::class);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route ("/login/level-two", name="login_level_two")
     */
    public function googleLogin(Request $request, SecurityGoogleAuth $googleAuthenticator, UserRepository $userRepo): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userSecret = $user->getSecret();
        if (!empty($user)) {
            $form = $this->createForm(LoginLevelTwoFormType::class);
            if (empty($userSecret)) {
                $secret = base64_decode($form->get('secret')->getData());
                $qrCode = GoogleQrUrl::generate($user->getEmail(), $secret, 'PassSafe');
            }
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var LoginTwoDTO $formData */
                $formData = $form->getData();
                $googleAuthenticator->authenticate($formData, $user);
                if ($user->isGoogleAuthenticate() == true) {
                    if (empty($userSecret)) {
                        $foundUser = $userRepo->findOneBy(['id' => $user->getId()]);
                        $foundUser->setSecret($formData->getSecret());
                        $userRepo->update($foundUser);
                    }
                    return $this->redirectToRoute('user_dashboard');
                } else {
                    $error = 'Невалиден код, моля, опитайте отново!';
                }
            }

        } else {
            return $this->redirectToRoute('login');
        }


        return $this->render('security/login.level-two.html.twig', [
            'form' => $form->createView(),
            'qrCode' => $qrCode ?? null,
            'error' => $error ?? null
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}