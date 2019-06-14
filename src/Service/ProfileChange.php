<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProfileChange
{
    private $em;
    private $container;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function changeProfile(User $user, $profile)
    {
        if ($profile != null)
        {
            // Save new profile
            $profileFilepath = $this->handleFile($profile);

            $user->setProfile($profileFilepath);
            $this->em->persist($user);
            $this->em->flush();
        }
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