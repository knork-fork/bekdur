<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;

class MapMessageAuthors
{
    private $em;
    private $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function map(Array $messages)
    {
        // Get all ids from messages
        $ids = array();
        foreach ($messages as $message)
        {
            $ids[] = $message->getAuthorId();
        }
        $ids = array_unique($ids);

        // Get users from ids
        $users = array();
        foreach ($ids as $id)
        {
            $user = $this->userRepository->findOneBy([ 
                'id' => $id
            ]);
            $users[$id] = $user->getUsername(); // to-do: first + last name
        }

        // Map users to messages (foreach)
        foreach ($messages as $message)
        {
            $message->setAuthorUsername($users[$message->getAuthorId()]);
        }

        return $messages;
    }
}