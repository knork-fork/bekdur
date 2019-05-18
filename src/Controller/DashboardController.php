<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\DashboardData;
use App\Service\Seener;

class DashboardController extends AbstractController
{
    private $tokenStorage;
    private $router;
    private $dashboardData;
    private $seener;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router, DashboardData $dashboardData, Seener $seener)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->dashboardData = $dashboardData;
        $this->seener = $seener;
    }

    public function dashboard($group_id = null, $inbox_id = null)
    {
        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            $this->seener->setNotificationsSeen($group_id, $user);
            $this->seener->setMessagesSeen($inbox_id, $user);

            $parameters = $this->dashboardData->getDashboardDataStatic($user, "Bekdur aplikacija", $group_id, $inbox_id);

            return $this->render("user/dashboard.html.twig", $parameters);
        }
        else
        {
            // Not logged in, redirect

            return new RedirectResponse($this->router->generate("user_login"));
        }
    }

    public function updates($group_id = null, $inbox_id = null)
    {
        // This route will be called from frontend (liveUpdater.js)
        // to-do: also pass group/inbox id (if any currently open) to automatically set notifications in them to seen

        if ($this->tokenStorage->getToken()->getUsername() !== "anon.")
        {
            // Logged in, continue

            $user = $this->tokenStorage->getToken()->getUser();

            // There's no need to display notifications when already inside group/inbox
            $this->seener->setNotificationsSeen($group_id, $user);
            $this->seener->setMessagesSeen($inbox_id, $user);
            
            $parameters = $this->dashboardData->getDashboardDataDynamic($user, $group_id, $inbox_id);

            return new JsonResponse($parameters);
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