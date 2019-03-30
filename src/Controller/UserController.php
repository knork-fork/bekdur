<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;


class UserController extends AbstractController
{
    private $tokenStorage;
    private $authUtils;
    private $router;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationUtils $authUtils, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authUtils = $authUtils;
        $this->router = $router;
    }

    public function login()
    {
        if ($this->authUtils->getLastAuthenticationError() != null)
            $errorMsg = "Neispravni podaci";
        else
            $errorMsg = "";

        if ($this->tokenStorage->getToken()->getUsername() === "anon.")
        {
            // Not logged in, continue

            return $this->render("user/login.html.twig", [
                "page_title" => "Bekdur aplikacija",
                "login_error" => $errorMsg,
            ]);
        }
        else
        {
            // Logged in, redirect

            // todo: redirect to user page here
            return new RedirectResponse($this->router->generate("index"));
        }
    }

    public function logout()
    {
        throw new \Exception("Something went wrong.");
    }

    public function signup()
    {
        if ($this->tokenStorage->getToken()->getUsername() === "anon.")
        {
            // Not logged in, continue

            return $this->render("user/signup.html.twig", [
                "page_title" => "Bekdur aplikacija",
            ]);
        }
        else
        {
            // Logged in, redirect

            // todo: redirect to user page here
            return new RedirectResponse($this->router->generate("index"));
        }
    }

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
        $user->setCreated(new \DateTime());

        $em->persist($user);
        $em->flush();

        return new Response("Added user: ".$user->getUsername());
    }
}