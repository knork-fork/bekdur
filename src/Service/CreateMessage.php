<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message;
use App\Entity\UserInbox;
use App\Entity\User;

class CreateMessage
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(UserInbox $inbox, string $text, User $author)
    {
        $users = $inbox->getInboxMemberships();

        foreach ($users as $user)
        {
            $id = $user->getInboxUser()->getId();

            // Don't add notification to author
            if ($id == $author->getId())
                continue;

            $msg = new Message();

            $msg->setUserInbox($inbox);
            $msg->setText($text);
            $msg->setSeen(false);
            $msg->setCreated(new \DateTime());
            $msg->setUserId($id);
            $msg->setAuthorId($author->getId());

            $this->em->persist($msg);     
        }

        // Send all messages at once
        $this->em->flush();
    }
}