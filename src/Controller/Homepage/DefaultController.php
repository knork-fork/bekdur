<?php

namespace App\Controller\Homepage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
    public function index(TokenStorageInterface $tokenStorage, AuthenticationUtils $authUtils)
    {
        if ($authUtils->getLastAuthenticationError() != null)
            $errorMsg = "Invalid login";
        else
            $errorMsg = "No error";

        echo $errorMsg;

        return $this->render("homepage/homepage.html.twig", [
            "page_title" => "Bekdur aplikacija",
            "user_name" => $tokenStorage->getToken()->getUsername(),
        ]);
    }
}