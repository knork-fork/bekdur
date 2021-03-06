<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
use App\Service\ProfileChange;

class UserController extends AbstractController
{
    private $tokenStorage;
    private $authUtils;
    private $router;
    private $userRegister;
    private $passwordEncoder;
    private $guardHandler;
    private $profileChange;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationUtils $authUtils, RouterInterface $router, UserRegister $userRegister, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, ProfileChange $profileChange)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authUtils = $authUtils;
        $this->router = $router;
        $this->userRegister = $userRegister;
        $this->passwordEncoder = $passwordEncoder;
        $this->guardHandler = $guardHandler;
        $this->profileChange = $profileChange;
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
                "page_title" => "Collabsy",
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

            $errorMsg = "";

            if ($form->isSubmitted() && $form->isValid())
            {
                $user = $form->getData();

                $registerOk = $this->userRegister->register($user, $errorMsg);
                
                if ($registerOk)
                {
                    return $this->guardHandler->authenticateUserAndHandleSuccess(
                        $user,
                        $request,
                        $loginInterface,
                        'main'
                    );
                }
            }

            // Generic error
            if ($form->isSubmitted() && !$form->isValid())
                $errorMsg = "Neispravni podaci";

            return $this->render("user/signup.html.twig", [
                "page_title" => "Collabsy",
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

    public function changeProfile(Request $request)
    {
        // Check if logged in, check if POST etc.

        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            if ($request->isMethod('POST'))
            {
                $image = $request->files->get('profile');

                $this->profileChange->changeProfile($user, $image);
            }
            
            // Redirect to homepage
            return new RedirectResponse($this->router->generate("user_dashboard"));
         }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
        }
    }
}