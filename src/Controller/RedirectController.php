<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    /**
     * @Route(path="", methods={"GET", "POST"})
     */
    public function homeToLogin()
    {
     return  $this->redirectToRoute('cp.login');
    }
}