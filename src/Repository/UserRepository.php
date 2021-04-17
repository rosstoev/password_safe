<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        parent::__construct($registry, User::class);
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function save(User $user)
    {
        $password = $user->getPassword();
        $hashPassword = $this->userPasswordEncoder->encodePassword($user, $password);
        $user->setPassword($hashPassword);
        $user->setRoles(['ROLE_USER']);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

    }
}
