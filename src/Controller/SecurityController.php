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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
     private $entityManager;
     /**
      * @var \Doctrine\Common\Persistence\ObjectRepository
      */
     private $userRepository;
  
     public function __construct(EntityManagerInterface $entityManager )
     {
         $this->entityManager = $entityManager;
         $this->userRepository = $entityManager->getRepository('App:User');
     }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {

        $error = $authUtils->getLastAuthenticationError();


        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/index.html.twig', [
            'last_name' => $lastUsername,
            'error' => $error,
        ]);
    }
    /**
     * @Route("/", name="loginVerify")
     */
     public function loginVerify()
     {
        return $this->render('home/verify2.html.twig');
     }

    /**
     * @Route("/", name="send")
     */
    public function sendSms(Request $request)
{
    $user = $this->getUser();
    $email = $user->getEmail();
    $phone = $user->getPhoneNumber();
    $countryCode = $user->getCountryCode();
    $password = $user->getPassword();
    $username = $user->getUsername();
    
 
    if ( $countryCode) {
 
       $authy_api = new \Authy\AuthyApi( getenv('TWILIO_AUTHY_API_KEY') );
       $user      = $authy_api->registerUser( $email, $phone, $countryCode );
 
       if ( $user->ok() ) {
 
           $sms = $authy_api->requestSms( $user->id(), [ "force" => "true" ] );
 
           if ( $sms->ok() ) {
 
               $this->addFlash(
                   'success',
                   $sms->message()
               );
           }
 
           $user_params = [
               'username' =>$username,
               'email' => $email,
               'country_code' => $countryCode,
               'phone_number' => $phone,
               'authy_id' => $user->id(),
               'password' => $password,
           ];
 
           $this->get('session')->set('user2', $user_params);
        }
        
    }
    return $this->redirectToRoute('verify_page2');
}
function updateDatabase($object)
{
    $this->entityManager->persist($object);
    $this->entityManager->flush();
}
    /**
     * @Route("/verify/code2", name="verify_code2")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
     public function verifyCode2(Request $request, UserPasswordEncoderInterface $encoder)
     {
         try {
             // Get data from session
                        $data = $this->get('session')->get('user2');
           
            $authy_api    = new \Authy\AuthyApi( getenv('TWILIO_AUTHY_API_KEY') );
            $verification = $authy_api->verifyToken( $data['authy_id'], $request->query->get('verify_code') );

 
            return $this->redirectToRoute('home');

         } catch (\Exception $exception) {
             $this->addFlash(
                 'error',
                 'Verification code is incorrect'
             );
             return $this->redirectToRoute('verify_page2');
         }
     }
     
  
}
