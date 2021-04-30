<?php


namespace App\Security;


use App\DTO\LoginTwoDTO;
use App\Entity\User;
use Sonata\GoogleAuthenticator\GoogleAuthenticator as GoogleAuthenticatorChecker;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GoogleAuthenticator
{
    private GoogleAuthenticatorChecker $googleAuthenticatorChecker;

    public function __construct(GoogleAuthenticatorChecker $googleAuthenticatorChecker)
    {
        $this->googleAuthenticatorChecker = $googleAuthenticatorChecker;
    }

    public function authenticate(LoginTwoDTO $data, User $user)
    {
        $userSecret = $user->getSecret();
        $code = $data->getCode();
        if (!empty($userSecret)) {
            $secret = base64_decode($userSecret);
        } else {
            $secret = base64_decode($data->getSecret());
        }

        $isValid = $this->googleAuthenticatorChecker->checkCode($secret, $code);
        if ($isValid){
            $user->setIsGoogleAuthenticate(true);
        }
    }
}