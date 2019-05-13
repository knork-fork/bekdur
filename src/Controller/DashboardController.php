<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\DashboardData;

class DashboardController extends AbstractController
{
    private $tokenStorage;
    private $router;
    private $dashboardData;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router, DashboardData $dashboardData)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->dashboardData = $dashboardData;
    }

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
            
            $parameters = $this->dashboardData->getDashboardDataDynamic($user, "Bekdur aplikacija", null, null);

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