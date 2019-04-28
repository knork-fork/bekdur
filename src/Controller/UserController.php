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
use App\Repository\NotificationRepository;
use App\Repository\UserGroupRepository;
use App\Repository\GroupMembershipRepository;
use App\Service\GroupNotificationNumber;

class UserController extends AbstractController
{
    private $tokenStorage;
    private $authUtils;
    private $router;
    private $userRegister;
    private $passwordEncoder;
    private $guardHandler;
    private $notificationRepository;
    private $userGroupRepository;
    private $groupMembershipRepository;
    private $groupNotificationNumber;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationUtils $authUtils, RouterInterface $router, UserRegister $userRegister, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, NotificationRepository $notificationRepository, UserGroupRepository $userGroupRepository, GroupMembershipRepository $groupMembershipRepository, GroupNotificationNumber $groupNotificationNumber)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authUtils = $authUtils;
        $this->router = $router;
        $this->userRegister = $userRegister;
        $this->passwordEncoder = $passwordEncoder;
        $this->guardHandler = $guardHandler;
        $this->notificationRepository = $notificationRepository;
        $this->userGroupRepository = $userGroupRepository;
        $this->groupMembershipRepository = $groupMembershipRepository;
        $this->groupNotificationNumber = $groupNotificationNumber;
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

            $user = $this->tokenStorage->getToken()->getUser();

            // to-do: Get latest, unseen user notifications
            $notifications = $this->notificationRepository->findBy([
                "userId" => $user->getId(),
                "seen" => false,
            ]);

            // to-do: Get groups user is in
            $groups = $this->groupMembershipRepository->findBy([
                "groupUser" => $user,
            ]);


            // Calculate notification num and save to each group
            foreach ($groups as $group)
            {
                $usergroup = $group->getUserGroup();
                $this->groupNotificationNumber->setGroupNotificationNumber($usergroup, $notifications);
            }

            return $this->render("user/dashboard.html.twig", [
                "page_title" => "Bekdur aplikacija",
                "notifications" => $notifications,
                "groups" => $groups,
            ]);
        }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
        }
    }

    public function updates()
    {
        // This route will be called from frontend (liveUpdater.js)
        // to-do: also pass group/inbox id (if any currently open) to automatically set notifications in them to seen

        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();
            
            // Get latest, unseen user notifications
            $notifications = $this->notificationRepository->findBy([
                "userId" => $user->getId(),
                "seen" => false,
            ]);

            // Get groups user is in
            $groups = $this->groupMembershipRepository->findBy([
                "groupUser" => $user,
            ]);

            // Calculate notification num and save to each group
            foreach ($groups as $group)
            {
                $usergroup = $group->getUserGroup();
                $this->groupNotificationNumber->setGroupNotificationNumber($usergroup, $notifications);
            }

            // To-do: message notifications

            return $this->render("user/elements/notification.html.twig", [
                "page_title" => "Bekdur aplikacija",
                "notifications" => $notifications,
                "groups" => $groups,
            ]);
        }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
        }
    }
}