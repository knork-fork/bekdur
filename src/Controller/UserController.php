<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    public function new()
    {
        return new Response("OK!");
    }

    public function new_sample(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        
        $user->setUsername("test_user_".rand(1, 100000));
        $user->setFirstName("Userko");
        $user->setLastName("Useric");
        $user->setPassword($encoder->encodePassword($user, "blank"));

        $em->persist($user);
        $em->flush();

        return new Response("Added user: ".$user->getUsername());
    }
}