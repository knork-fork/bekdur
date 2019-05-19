<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message;
use App\Entity\UserInbox;

class CreateMessage
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(int $inbox_id, string $text, int $user_id, int $author_id)
    {
        // Reference (just to save inbox id)
        $inbox = $this->em->getReference("App\Entity\UserInbox", $inbox_id);

        // Create new message
        $msg = new Message();

        $msg->setUserInbox($inbox);
        $msg->setText($text);
        $msg->setSeen(false);
        $msg->setCreated(new \DateTime());
        $msg->setUserId($user_id);
        $msg->setAuthorId($author_id);

        $this->em->persist($msg);
        $this->em->flush();
    }
}