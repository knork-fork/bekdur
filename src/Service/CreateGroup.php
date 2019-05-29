<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserGroup;
use App\Entity\User;
use App\Entity\GroupMembership;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateGroup
{
    private $em;
    private $container;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function create($user, $name, $profile, $background, $theme): int
    {
        if ($name == "")
            $name = "Nova Grupa";
        $profileFilepath = null;
        $backgroundFilepath = null;

        if ($profile != null)
        {
            // Profile was sent
            $profileFilepath = $this->handleFile($profile);
        }

        if ($background != null)
        {
            // Background was sent
            $backgroundFilepath = $this->handleFile($background);
        }

        $group = new UserGroup();

        $group->setName($name);
        $group->setCreated(new \DateTime());
        $group->setProfile($profileFilepath);
        $group->setBackground($backgroundFilepath);
        $group->setTheme($theme);

        $this->em->persist($group);
        $this->em->flush();

        // Join current user to group
        $this->joinGroup($user, $group);

        return $group->getId();
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

    public function handleFile(UploadedFile $file) : string
    {
        $original = $file->getClientOriginalName();

        $filename = $this->generateUniqueFileName();
        $extension = $file->guessExtension();

        $filename = $filename.".".$extension;
        $filepath = $this->container->getParameter("upload_directory");

        $file->move(
            $filepath,
            $filename
        );

        $ret = [
            "filepath" => $filepath."/".$filename,
            "original" => $original
        ];

        $ret = json_encode($ret);

        return $ret;
    }

    public function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}