<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\NotificationRepository;
use App\Repository\UserGroupRepository;
use App\Repository\GroupMembershipRepository;
use App\Service\GroupNotificationNumber;
use App\Repository\InboxMembershipRepository;
use App\Service\InboxMessageNumber;
use App\Repository\MessageRepository;
use App\Repository\GroupPostRepository;

class DashboardData
{
    private $templating;
    private $notificationRepository;
    private $userGroupRepository;
    private $groupMembershipRepository;
    private $groupNotificationNumber;
    private $inboxMembershipRepository;
    private $inboxMessageNumber;
    private $messageRepository;
    private $postRepository;

    public function __construct(\Twig_Environment $templating, NotificationRepository $notificationRepository, UserGroupRepository $userGroupRepository, GroupMembershipRepository $groupMembershipRepository, GroupNotificationNumber $groupNotificationNumber, InboxMembershipRepository $inboxMembershipRepository, InboxMessageNumber $inboxMessageNumber, MessageRepository $messageRepository, GroupPostRepository $postRepository)
    {
        $this->templating = $templating;
        $this->notificationRepository = $notificationRepository;
        $this->userGroupRepository = $userGroupRepository;
        $this->groupMembershipRepository = $groupMembershipRepository;
        $this->groupNotificationNumber = $groupNotificationNumber;
        $this->inboxMembershipRepository = $inboxMembershipRepository;
        $this->inboxMessageNumber = $inboxMessageNumber;
        $this->messageRepository = $messageRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Called on page load
     */
    public function getDashboardDataStatic(User $user, string $pageTitle, $group_id = null, $inbox_id = null) : Array
    {
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

        // to-do: for multi-user inboxes just get inbox name instead, otherwise get name of the other user
        foreach ($inboxes as $inbox)
        {
            $inb = $inbox->getUserInbox();

            // Find other user in inbox
            $other = $this->inboxMembershipRepository->getOtherInboxUser($user, $inb);
            $inb->setName($other->getUsername()); // to-do: change to first+last name

            $this->inboxMessageNumber->setInboxMessageNumber($inb, $messages);
        }

        // Get group content
        if (isset($group_id))
            $groupPosts = $this->postRepository->findByGroupId($group_id);
        else
            $groupPosts = null;

        // Get inbox content - to-do
        if (isset($inbox_id))
            $inboxMessages = null;
        else
            $inboxMessages = null;

        return [
            "page_title" => "Bekdur aplikacija",
            "notifications" => $notifications,
            "groups" => $groups,
            "inboxes" => $inboxes,
            "posts" => $groupPosts,
            "messages" => $inboxMessages,
        ];
    }

    /**
     * Called from frontend in update function
     */
    public function getDashboardDataDynamic(User $user, string $pageTitle, $group_id = null, $inbox_id = null) : Array
    {
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

        $notificationView = $this->templating->render("user/elements/notification.html.twig", [
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

        return [
            "notifications" => $notificationView,
            "notificationNumbers" => $notificationNumbers,
            "messageNumbers" => $messageNumbers,
            "request" => "OK",
        ];
    }
}