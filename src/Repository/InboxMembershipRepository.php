<?php

namespace App\Repository;

use App\Entity\InboxMembership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;
use App\Entity\UserInbox;

/**
 * @method InboxMembership|null find($id, $lockMode = null, $lockVersion = null)
 * @method InboxMembership|null findOneBy(array $criteria, array $orderBy = null)
 * @method InboxMembership[]    findAll()
 * @method InboxMembership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InboxMembershipRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InboxMembership::class);
    }

    public function getOtherInboxUser(User $user, UserInbox $inbox) : User
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.userInbox = :inbox')
            ->setParameter('inbox', $inbox)
            ->andWhere('i.inboxUser != :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
            ->getInboxUser()
        ;
    }

    // /**
    //  * @return InboxMembership[] Returns an array of InboxMembership objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InboxMembership
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
