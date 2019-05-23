<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\GroupPost;
use App\Entity\User;
use App\Entity\UserGroup;

class CreatePost
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(User $author, UserGroup $group, string $content, $image, $file)
    {
        if ($image != null)
        {
            // Image was sent
            $header = "šalje sliku";
            $type = 2;
        }
        else if ($file != null)
        {
            // File was sent
            $header = "šalje datoteku";
            $type = 3;
        }
        else
        {
            // Text was sent
            $header = "objavljuje";
            $type = 1;
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

        $this->em->persist($post);
        $this->em->flush();

        // Pass on header to CreateNotification
        return $header;
    }
}