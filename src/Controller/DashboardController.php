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
use App\Repository\InboxMembershipRepository;
use App\Service\InboxMessageNumber;
use App\Repository\MessageRepository;
use App\Repository\GroupPostRepository;
use App\Service\DashboardData;

class DashboardController extends AbstractController
{
    private $tokenStorage;
    private $router;
    private $notificationRepository;
    private $userGroupRepository;
    private $groupMembershipRepository;
    private $groupNotificationNumber;
    private $inboxMembershipRepository;
    private $inboxMessageNumber;
    private $messageRepository;
    private $postRepository;
    private $dashboardData;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router, NotificationRepository $notificationRepository, UserGroupRepository $userGroupRepository, GroupMembershipRepository $groupMembershipRepository, GroupNotificationNumber $groupNotificationNumber, InboxMembershipRepository $inboxMembershipRepository, InboxMessageNumber $inboxMessageNumber, MessageRepository $messageRepository, GroupPostRepository $postRepository, DashboardData $dashboardData)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->notificationRepository = $notificationRepository;
        $this->userGroupRepository = $userGroupRepository;
        $this->groupMembershipRepository = $groupMembershipRepository;
        $this->groupNotificationNumber = $groupNotificationNumber;
        $this->inboxMembershipRepository = $inboxMembershipRepository;
        $this->inboxMessageNumber = $inboxMessageNumber;
        $this->messageRepository = $messageRepository;
        $this->postRepository = $postRepository;
        $this->dashboardData = $dashboardData;
    }

    // to-do: This is not how a controller should look like... Take away duplicate code and move it to some service or something.

    public function dashboard($group_id = null, $inbox_id = null)
    {
        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            $parameters = $this->dashboardData->getDashboardDataStatic($user, "Bekdur aplikacija", $group_id, $inbox_id);

            return $this->render("user/dashboard.html.twig", $parameters);
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

            $notificationView = $this->renderView("user/elements/notification.html.twig", [
                "page_title" => "Bekdur aplikacija",
                "notifications" => $notifications,
            ]);

            // Get latest, unseen messages - userId marks "the target", or the "other" user in an inbox
            // to-do: for multi-user support, send message to multiple targets?
            $messages = $this->messageRepository->findBy([
                "userId" => $user->getId(),
                "seen" => false,
            ]);

            // Get inboxes user is in
            $inboxes = $this->inboxMembershipRepository->findBy([
                "inboxUser" => $user,
            ]);

            // Get array with key (inbox id) value (message number) pairs
            $messageNumbers = $this->inboxMessageNumber->calculate($inboxes, $messages);

            return new JsonResponse([
                "notifications" => $notificationView,
                "notificationNumbers" => $notificationNumbers,
                "messageNumbers" => $messageNumbers,
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