<?php

namespace App\Controller\Homepage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Service\MobileDetector;
class DefaultController extends AbstractController
{
    public function index(TokenStorageInterface $tokenStorage, MobileDetector $mobileDetector)
    {
        //dd($mobileDetector->isMobile());

        return $this->render("homepage/homepage.html.twig", [
            "page_title" => "Collabsy",
            "user_name" => $tokenStorage->getToken()->getUsername(),
        ]);
    }
}