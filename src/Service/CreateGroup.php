<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserGroup;
use App\Entity\User;
use App\Entity\GroupMembership;

class CreateGroup
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create($user, $name)
    {
        // Create group
        $group = new UserGroup();

        $group->setName($name);
        $group->setCreated(new \DateTime());

        $this->em->persist($group);
        $this->em->flush();

        // Join current user to group
        $this->joinGroup($user, $group);
    }

    public function joinGroup(User $user, UserGroup $group)
    {
        $membership = new GroupMembership();

        $membership->setGroupUser($user);
        $membership->setUserGroup($group);
        $membership->setCreated(new \DateTime());

        $this->em->persist($membership);
        $this->em->flush();
    }
}