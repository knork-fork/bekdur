<?php

namespace App\Repository;

use App\Entity\GroupComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupComment[]    findAll()
 * @method GroupComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupComment::class);
    }

    // /**
    //  * @return GroupComment[] Returns an array of GroupComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupComment
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
