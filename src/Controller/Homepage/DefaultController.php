<?php

namespace App\Controller\Homepage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class DefaultController extends AbstractController
{
    public function index()
    {
        $user = new User();

        return $this->render("homepage/homepage.html.twig", [
            "page_title" => "Bekdur aplikacija",
            "user_name" => $user->name,
        ]);
    }
}