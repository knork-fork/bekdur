<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\InboxMembershipRepository;
use App\Service\CreateInbox;
use App\Service\CreateMessage;

class InboxController extends AbstractController
{ 
    private $em;
    private $inboxMembershipRepository;
    private $tokenStorage;
    private $router;
    private $createInbox;
    private $createMessage;

    public function __construct(EntityManagerInterface $em, InboxMembershipRepository $inboxMembershipRepository, TokenStorageInterface $tokenStorage, RouterInterface $router, CreateInbox $createInbox, CreateMessage $createMessage)
    {
        $this->em = $em;
        $this->inboxMembershipRepository = $inboxMembershipRepository;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->createInbox = $createInbox;
        $this->createMessage = $createMessage;
    }

    public function enter($user_id = null)
    {
        // Security to-do: send user_id in post

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

    public function createMessage(Request $request)
    {
        // Check if logged in, check if POST etc.

        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            if ($request->isMethod('POST'))
            {
                $inbox_id = $request->request->get('inboxId');
                $message = $request->request->get('message');

                // Reference (just to save inbox id)
                $inbox = $this->em->getReference("App\Entity\UserInbox", $inbox_id);

                $this->createMessage->create($inbox, $message, $user);

                // Return updates from that inbox
                return new RedirectResponse($this->router->generate("inbox_updates", array("inbox_id" => $inbox_id)));
            }
            
            // Return generic update
            return new RedirectResponse($this->router->generate("user_updates"));
         }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
        }
    }
}