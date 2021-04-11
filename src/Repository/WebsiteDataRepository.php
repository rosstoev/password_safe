<?php

namespace App\Repository;

use App\Entity\WebsiteData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebsiteData|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsiteData|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsiteData[]    findAll()
 * @method WebsiteData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsiteData::class);
    }

    // /**
    //  * @return WebsiteData[] Returns an array of WebsiteData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebsiteData
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
