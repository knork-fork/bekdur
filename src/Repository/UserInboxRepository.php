<?php

namespace App\Repository;

use App\Entity\UserInbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserInbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInbox[]    findAll()
 * @method UserInbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInboxRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserInbox::class);
    }

    // /**
    //  * @return UserInbox[] Returns an array of UserInbox objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserInbox
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
