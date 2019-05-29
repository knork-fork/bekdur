<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use App\Service\CreateGroup;
use App\Service\CreatePost;
use App\Service\CreateComment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Service\CreateNotification;

class GroupController extends AbstractController
{ 
    private $em;
    private $tokenStorage;
    private $router;
    private $createGroup;
    private $createPost;
    private $createComment;
    private $createNotification;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, RouterInterface $router, CreateGroup $createGroup, CreatePost $createPost, CreateComment $createComment, CreateNotification $createNotification)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->createGroup = $createGroup;
        $this->createPost = $createPost;
        $this->createComment = $createComment;
        $this->createNotification = $createNotification;
    }

    public function createGroup(Request $request)
    {
        // Check if logged in, check if POST etc.

        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            if ($request->isMethod('POST'))
            {
                $name = $request->request->get('groupName');
                $profile = $request->files->get('profile');
                $background = $request->files->get('background');
                $theme = $request->request->get('theme');

                $group_id = $this->createGroup->create($user, $name, $profile, $background, $theme);
            
                return new RedirectResponse($this->router->generate("group_dashboard", array("group_id" => $group_id)));
            }
            else
                return new RedirectResponse($this->router->generate("user_dashboard"));
        }

        return new Response("OK!");
    }

    public function joinGroup($group_id)
    {
        // Security to-do: send group_id in post or via invite link

        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            // Reference (just to save group id)
            $group = $this->em->getReference("App\Entity\UserGroup", $group_id);

            $this->createGroup->joinGroup($user, $group);

            // Redirect to group
            return new RedirectResponse($this->router->generate("group_dashboard", array("group_id" => $group_id)));
        }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
        }
    }

    public function createPost(Request $request)
    {
        // Check if logged in, check if POST etc.

        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            if ($request->isMethod('POST'))
            {
                $group_id = $request->request->get('groupId');
                $image = $request->files->get('image');
                $file = $request->files->get('file');

                $content = $request->request->get('content');

                // Reference (just to save group id)
                $group = $this->em->getReference("App\Entity\UserGroup", $group_id);

                $header = $this->createPost->create($user, $group, $content, $image, $file);

                // Create a notification for all group members except author of post
                $this->createNotification->create($user, $group, $content, $header);
            
                return new RedirectResponse($this->router->generate("group_dashboard", array("group_id" => $group_id)));
            }
            else
                return new RedirectResponse($this->router->generate("user_dashboard"));
         }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
        }
    }

    public function createComment(Request $request)
    {
        // Check if logged in, check if POST etc.

        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            if ($request->isMethod('POST'))
            {
                $group_id = $request->request->get('groupId');
                $groupPostId = $request->request->get('groupPostId');
                $text = $request->request->get('text');

                // Reference (just to save post id)
                $post = $this->em->getReference("App\Entity\GroupPost", $groupPostId);

                $this->createComment->create($user, $post, $text);

                // Return updates from that group
                return new RedirectResponse($this->router->generate("group_updates", array("group_id" => $group_id)));
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