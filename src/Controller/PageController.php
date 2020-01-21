<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/hello", name="hello_page")
     */
    public function index()
    {

        return $this->render('page/index.html.twig');
    }

    /**
     * @Route("/auth", name="auth_page")
     * @IsGranted("ROLE_USER")
     */
    public function auth()
    {
        return $this->render('page/auth.html.twig');
    }

    /**
     * @Route("/admin", name="admin_page")
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin()
    {
        return $this->render('page/auth.html.twig');
    }

    /**
     * @Route("/mail", name="mail_page")
     */
    public function mail(\Swift_Mailer $mailer): Response
    {
        $message = (new \Swift_Message("salut" , "salut"))
            ->setSubject("Je test mes TESTS")
            ->setFrom("kharkhachissam@gmail.com")
            ->setTo("kharkhachissam@gmail.com");
        $mailer->send($message);
        return new Response("hello");

    }


}
