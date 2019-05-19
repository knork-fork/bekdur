<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\UserInbox;
use App\Entity\InboxMembership;

class CreateInbox
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(int $user1, int $user2) : int
    {
        // Create new inbox
        $inbox = new UserInbox();

        $inbox->setName("user_inbox");
        $inbox->setCreated(new \DateTime());
        $inbox->setMostRecent(new \DateTime());
        $this->em->persist($inbox);
        $this->em->flush();

        $inbox_id = $inbox->getId();

        // Add users to it
        $conn = $this->em->getConnection();

        $q = "INSERT INTO inbox_membership
                (inbox_user_id, user_inbox_id, created) VALUES 
                    (?, ?, now()),
                    (?, ?, now());";
           
        $pst = $conn->prepare($q);
        $pst->bindValue(1, $user1);
        $pst->bindValue(2, $inbox_id);
        $pst->bindValue(3, $user2);
        $pst->bindValue(4, $inbox_id);

        $pst->execute();

        return $inbox_id;
    }
}