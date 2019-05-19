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

    public function create(User $author, UserGroup $group, string $header, string $content)
    {
        $post = new GroupPost();

        $post->setAuthor($author);
        $post->setUserGroup($group);
        $post->setHeader($header);
        $post->setContent($content);
        $post->setPoints(0);
        $post->setCreated(new \DateTime());
        $post->setMostRecent(new \DateTime());

        $this->em->persist($post);
        $this->em->flush();
    }
}