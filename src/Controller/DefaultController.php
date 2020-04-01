<?php

namespace App\Controller;

/**--***************************************************************************************
*    Title: Verify Phone Numbers in Symfony 4 PHP with Authy and Twilio SMS
*    Author: Oluyemi Olususi
*    Date: 29-05-2019
*    Code version: 1
*    Availability: https://www.twilio.com/blog/verify-phone-numbers-in-symfony-4-php-with-authy-and-twilio-sms
*
***************************************************************************************/

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }
    /**
     * @Route("/", name="app_login")
     */
     public function app()
     {
         return $this->render('home/index.html.twig');
     }
     /**
     * @Route("/", name="verify_page2")
     */
     public function verifyPage2()
     {
         return $this->render('home/verify2.html.twig');
     }
     
}