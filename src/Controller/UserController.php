<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractController
{
    public function new()
    {
        return new Response("OK!");
    }
}