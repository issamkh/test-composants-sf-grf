<?php
/**
 * Created by PhpStorm.
 * User: issam
 * Date: 2020-01-12
 * Time: 14:20
 */

namespace App\Tests\Controller;


use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PageControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testHelloController(){

        $client = static::createClient();
        $client->request("GET", "/hello");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    public function testH1HelloPage(){

        $client = static::createClient();
        $crawler = $client->request("GET", "/hello");
        $this->assertSelectorTextContains("h1","Hello");
    }

    public function testAuthPageIsRestricted(){

        $client = static::createClient();
        $client->request("GET", "/auth");
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }

    public function testRedirectToLogin(){

        $client = static::createClient();
        $client->request("GET", "/auth");
        $this->assertResponseRedirects("/login");

    }

    public function testAuthenticatedUserAccessToPageAuth(){

        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__. '/UserSecurityTestFixtures.yaml']);
        $this->Login($client, $users["user_user"]);
        $client->request("GET", "/auth");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

   /* public function testForbidenAccessToPageAdmin(){

        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__. '/UserSecurityTestFixtures.yaml']);
        $this->Login($client, $users["user_user"]);
        $client->request("GET", "/admin");
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }*/

    public function testAccessToPageAdmin(){

        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__. '/UserSecurityTestFixtures.yaml']);
        $this->Login($client, $users["user_admin"]);
        $client->request("GET", "/admin");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSendMail(){
        $client = static::createClient();
        $client->enableProfiler();
        $client->request("GET", "/mail");
        $mailcollector = $client->getProfile()->getCollector("swiftmailer");
        $this->assertEquals(1, $mailcollector->getMessageCount());

        /** @var \Swift_Message $message */
        $message = $mailcollector->getMessages();
        $this->assertEquals($message[0]->getTo(), ["kharkhachissam@gmail.com" => null]);

    }
}