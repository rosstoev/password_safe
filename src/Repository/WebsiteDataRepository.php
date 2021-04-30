<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\WebsiteData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method WebsiteData|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsiteData|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsiteData[]    findAll()
 * @method WebsiteData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteDataRepository extends ServiceEntityRepository
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ManagerRegistry $registry, ParameterBagInterface $parameterBag)
    {
        parent::__construct($registry, WebsiteData::class);
        $this->parameterBag = $parameterBag;
    }

    public function save(WebsiteData $websiteData, User $userData)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {
            $encryptPassword = $websiteData->encryptPassword($this->parameterBag);
            $websiteData->setPassword($encryptPassword);
            $websiteData->setUser($userData);
            $em->persist($websiteData);
            $em->flush();
            $em->commit();
        } catch (\Exception $ex) {
            $em->rollback();
            throw $ex;
        }

    }

    public function remove(WebsiteData $websiteData)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{
            $em->remove($websiteData);
            $em->flush();
            $em->commit();
        } catch (\Exception $ex){
            $em->rollback();
            throw $ex;
        }
    }
}
