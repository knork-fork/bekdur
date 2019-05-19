<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\InboxMembershipRepository;
use App\Service\CreateInbox;

class InboxController extends AbstractController
{ 
    private $inboxMembershipRepository;
    private $tokenStorage;
    private $router;
    private $createInbox;

    public function __construct(InboxMembershipRepository $inboxMembershipRepository, TokenStorageInterface $tokenStorage, RouterInterface $router, CreateInbox $createInbox)
    {
        $this->inboxMembershipRepository = $inboxMembershipRepository;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->createInbox = $createInbox;
    }

    public function enter($user_id = null)
    {
        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            // Check if inbox already exists for current user and target user
            $inbox_id = $this->inboxMembershipRepository->getInboxWithTwoUsers($user->getId(), $user_id);

            // Create if it doesn't and add users to it
            if ($inbox_id === null)
            {
                $inbox_id = $this->createInbox->create($user->getId(), $user_id);
            }

            // Redirect to inbox
            return new RedirectResponse($this->router->generate("inbox_dashboard", array("inbox_id" => $inbox_id)));
        }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
        }
    }
}