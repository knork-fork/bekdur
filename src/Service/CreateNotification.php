<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Notification;
use App\Entity\User;
use App\Entity\UserGroup;

class CreateNotification
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(User $author, UserGroup $group, string $content, string $header)
    {
        // Trim content
        $content = substr($content, 0, 50)."...";

        $header = $author->getUsername()." ".$header;

        $users = $group->getGroupMemberships();

        foreach ($users as $user)
        {
            $id = $user->getGroupUser()->getId();

            // Don't add notification to author
            if ($id == $author->getId())
                continue;

            $notification = new Notification();

            $notification->setUserGroup($group);
            $notification->setTitle($header);
            $notification->setText($content);
            $notification->setSeen(false);
            $notification->setPushed(false);
            $notification->setCreated(new \DateTime());
            $notification->setUserId($id);

            $this->em->persist($notification);     
        }

        // Send all notifications at once
        $this->em->flush();
    }
}