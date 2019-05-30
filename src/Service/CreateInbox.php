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
        $inboxMembership1 = new InboxMembership();
        $inboxMembership2 = new InboxMembership();

        // Reference (just to save user 1)
        $userRef1 = $this->em->getReference("App\Entity\User", $user1);
        $inboxMembership1->setInboxUser($userRef1);
        $inboxMembership1->setUserInbox($inbox);
        $this->em->persist($inboxMembership1);

        // Reference (just to save user 2)
        $userRef2 = $this->em->getReference("App\Entity\User", $user2);
        $inboxMembership2->setInboxUser($userRef2);
        $inboxMembership2->setUserInbox($inbox);
        $this->em->persist($inboxMembership2);

        $this->em->flush();

        return $inbox_id;
    }
}