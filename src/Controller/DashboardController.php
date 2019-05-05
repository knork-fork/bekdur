<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\NotificationRepository;
use App\Repository\UserGroupRepository;
use App\Repository\GroupMembershipRepository;
use App\Service\GroupNotificationNumber;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends AbstractController
{
    private $tokenStorage;
    private $router;
    private $notificationRepository;
    private $userGroupRepository;
    private $groupMembershipRepository;
    private $groupNotificationNumber;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router, NotificationRepository $notificationRepository, UserGroupRepository $userGroupRepository, GroupMembershipRepository $groupMembershipRepository, GroupNotificationNumber $groupNotificationNumber)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->notificationRepository = $notificationRepository;
        $this->userGroupRepository = $userGroupRepository;
        $this->groupMembershipRepository = $groupMembershipRepository;
        $this->groupNotificationNumber = $groupNotificationNumber;
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

            // Get array with key (group id) value (notification number) pairs
            $notificationNumbers = $this->groupNotificationNumber->calculate($groups, $notifications);

            // To-do: message notifications

            $notificationView = $this->renderView("user/elements/notification.html.twig", [
                "page_title" => "Bekdur aplikacija",
                "notifications" => $notifications,
            ]);

            return new JsonResponse([
                "notifications" => $notificationView,
                "notificationNumbers" => $notificationNumbers,
                "request" => "OK",
            ]);
        }
        else
        {
            // Not logged in

            // to-do: 403 exception?

            return new JsonResponse([
                "request" => "NOT OK",
            ]);
        }
    }
}