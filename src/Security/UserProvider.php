<?php


namespace App\Security;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {

    }

    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->userRepository->findOneBy(['email' => $username]);

        if (empty($user)) {
            throw new UsernameNotFoundException(sprintf('Потребител с email %s не същетсвува', $username));
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException('Невалиден потребител');
        }

        return $user;
    }

    public function supportsClass($class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}