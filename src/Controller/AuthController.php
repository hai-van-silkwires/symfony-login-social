<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends AbstractController
{
    public function index()
    {
        return $this->render('auth/login.html.twig');
    }

    public function login(Request $request)
    {

    }
}
