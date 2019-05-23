<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\GroupPost;
use App\Entity\User;
use App\Entity\UserGroup;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreatePost
{
    private $em;
    private $container;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function create(User $author, UserGroup $group, string $content, $image, $file)
    {
        if ($image != null)
        {
            // Image was sent
            $header = "šalje sliku";
            $type = 2;
            $filepath = $this->handleFile($image);
        }
        else if ($file != null)
        {
            // File was sent
            $header = "šalje datoteku";
            $type = 3;
            $filepath = $this->handleFile($file);
        }
        else
        {
            // Text was sent
            $header = "objavljuje";
            $type = 1;
            $filepath = null;
        }

        $post = new GroupPost();

        $post->setAuthor($author);
        $post->setUserGroup($group);
        $post->setHeader($header);
        $post->setContent($content);
        $post->setType($type);
        $post->setPoints(0);
        $post->setCreated(new \DateTime());
        $post->setMostRecent(new \DateTime());
        $post->setFilePath($filepath);

        $this->em->persist($post);
        $this->em->flush();

        // Pass on header to CreateNotification
        return $header;
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