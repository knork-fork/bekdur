<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\GroupComment;
use App\Entity\User;
use App\Entity\GroupPost;

class CreateComment
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(User $author, GroupPost $post, string $text)
    {
        $comment = new GroupComment();

        $comment->setAuthor($author);
        $comment->setParent($post);
        $comment->setText($text);
        $comment->setCreated(new \DateTime());

        $this->em->persist($comment);
        $this->em->flush();

        // Update post most recent
        $post->setMostRecent(new \DateTime());
        
        $this->em->persist($post);
        $this->em->flush();
    }
}