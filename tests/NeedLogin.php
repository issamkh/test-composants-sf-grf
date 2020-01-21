<?php
/**
 * Created by PhpStorm.
 * User: issam
 * Date: 2020-01-13
 * Time: 14:44
 */

namespace App\Tests;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait NeedLogin
{

    public function Login(KernelBrowser $client,User $user){

        /**
         * On simule une authentification
         * @var  $session
         */
        $session = $client->getContainer()->get('session');
        $token = new UsernamePasswordToken($user, null, 'main',$user->getRoles());
        $session->set("_security_main",serialize($token));
        $session->save();

        /** on associe notre session au client */
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

    }
}