<?php

namespace App\Repository;

use App\Entity\InboxMembership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;
use App\Entity\UserInbox;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method InboxMembership|null find($id, $lockMode = null, $lockVersion = null)
 * @method InboxMembership|null findOneBy(array $criteria, array $orderBy = null)
 * @method InboxMembership[]    findAll()
 * @method InboxMembership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InboxMembershipRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, InboxMembership::class);
        $this->em = $em;
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

    public function getInboxWithTwoUsers(int $user1, int $user2)
    {
        $conn = $this->em->getConnection();

        $q = "SELECT i1.user_inbox_id FROM inbox_membership AS i1
                JOIN inbox_membership AS i2 on i1.user_inbox_id = i2.user_inbox_id
                WHERE i1.inbox_user_id = ? AND i2.inbox_user_id = ?;";
        
        $pst = $conn->prepare($q);
        $pst->bindValue(1, $user1);
        $pst->bindValue(2, $user2);

        $pst->execute(); 

        return $pst->fetch()["user_inbox_id"];
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
