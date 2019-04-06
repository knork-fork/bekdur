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
use App\Service\UserRegister;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserRegistrationType;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\LoginAuthenticator;


class UserController extends AbstractController
{
    private $tokenStorage;
    private $authUtils;
    private $router;
    private $userRegister;
    private $passwordEncoder;
    private $guardHandler;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationUtils $authUtils, RouterInterface $router, UserRegister $userRegister, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authUtils = $authUtils;
        $this->router = $router;
        $this->userRegister = $userRegister;
        $this->passwordEncoder = $passwordEncoder;
        $this->guardHandler = $guardHandler;
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

            return new RedirectResponse($this->router->generate("user_dashboard"));
        }
    }

    public function logout()
    {
        throw new \Exception("Something went wrong.");
    }

    public function signup(Request $request, LoginAuthenticator $loginInterface)
    {
        if ($this->tokenStorage->getToken()->getUsername() === "anon.")
        {
            // Not logged in, continue

            $form = $this->createForm(UserRegistrationType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $user = $form->getData();

                // to-do: Move all user-adding stuff here
                //$this->userRegister->register($user);
                // also check if fields are valid there
                
                // to-do: check if duplicate!
                
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                ));

                $user->setCreated(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $loginInterface,
                    'main'
                );
            }

            // to-do: get specific error
            if ($form->isSubmitted() && !$form->isValid())
                $errorMsg = "Neispravni podaci";
            else
                $errorMsg = "";

            return $this->render("user/signup.html.twig", [
                "page_title" => "Bekdur aplikacija",
                "registrationForm" => $form->createView(),
                "register_error" => $errorMsg,
            ]);
        }
        else
        {
            // Logged in, redirect

            return new RedirectResponse($this->router->generate("user_dashboard"));
        }
    }

    public function dashboard()
    {
        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            return $this->render("user/dashboard.html.twig", [
                "page_title" => "Bekdur aplikacija",
            ]);
        }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
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