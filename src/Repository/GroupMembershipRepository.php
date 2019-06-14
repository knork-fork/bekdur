<?php

namespace App\Repository;

use App\Entity\GroupMembership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserGroup;

/**
 * @method GroupMembership|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupMembership|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupMembership[]    findAll()
 * @method GroupMembership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupMembershipRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, GroupMembership::class);
        $this->em = $em;
    }

    public function getOtherGroups(User $user)
    {
        $conn = $this->em->getConnection();

        $q = "SELECT * FROM user_group
                WHERE id NOT IN(
                    SELECT user_group_id FROM group_membership
                    WHERE group_user_id = ?
                );";
        
        $pst = $conn->prepare($q);
        $pst->bindValue(1, $user->getId());

        $pst->execute(); 

        $groups = $pst->fetchAll();
        $groupObjects = array();

        // Turn arrays into objects
        foreach ($groups as $group)
        {
            $groupObject = $this->em->getReference("App\Entity\UserGroup", $group["id"]);

            $groupObjects[] = $groupObject;
        }

        return $groupObjects;
    }

    public function getCommonGroups(User $user1, User $user2)
    {
        $conn = $this->em->getConnection();

        $q = "SELECT DISTINCT user_group_id FROM group_membership
                WHERE group_user_id = ?
                AND user_group_id IN (
                    SELECT user_group_id FROM group_membership
                    WHERE group_user_id = ?
                );";
        
        $pst = $conn->prepare($q);
        $pst->bindValue(1, $user1->getId());
        $pst->bindValue(2, $user2->getId());

        $pst->execute(); 

        $groups = $pst->fetchAll();
        $groupObjects = array();

        // Turn arrays into objects
        foreach ($groups as $group)
        {
            $groupObject = $this->em->getReference("App\Entity\UserGroup", $group["user_group_id"]);

            $groupObjects[] = $groupObject;
        }

        return $groupObjects;
    }

    // /**
    //  * @return GroupMembership[] Returns an array of GroupMembership objects
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
    public function findOneBySomeField($value): ?GroupMembership
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
