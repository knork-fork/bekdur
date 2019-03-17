<?php

namespace App\Controller\Homepage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render("homepage/homepage.html.twig", [
            "page_title" => "Bekdur aplikacija",
            "user_name" => "default_user",
        ]);
    }
}