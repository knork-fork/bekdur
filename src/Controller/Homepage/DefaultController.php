<?php

namespace App\Controller\Homepage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultController extends AbstractController
{
    public function index(TokenStorageInterface $tokenStorage)
    {
        return $this->render("homepage/homepage.html.twig", [
            "page_title" => "Bekdur aplikacija",
            "user_name" => $tokenStorage->getToken()->getUsername(),
        ]);
    }
}