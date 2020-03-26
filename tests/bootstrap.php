<?php

/**--***************************************************************************************
*    Title: Verify Phone Numbers in Symfony 4 PHP with Authy and Twilio SMS
*    Author: Oluyemi Olususi
*    Date: 29-05-2019
*    Code version: 1
*    Availability: https://www.twilio.com/blog/verify-phone-numbers-in-symfony-4-php-with-authy-and-twilio-sms
*
***************************************************************************************/

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
