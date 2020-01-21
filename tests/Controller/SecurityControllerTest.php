<?php
/**
 * Created by PhpStorm.
 * User: issam
 * Date: 2020-01-12
 * Time: 15:17
 */

namespace App\Tests\Controller;


use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;
    public function testDisplayLogin(){

        $client = static::createClient();
        $client->request("GET", "/login");
        $this->assertSelectorNotExists(".alert.alert-danger");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Tester le formulaire avec des données erronées
     */
    public function testLoginWithBadCredentials(){

        $client = static::createClient();
        $crawler = $client->request("GET", "/login");

        /**
         * Remplissage le formulaire avec nos données
         */
        $form = $crawler->selectButton("Se connecter")->form([
            "email"=> "issam@gmail.com",
            "password"=>"fakeOne"
        ]);

        /**
         * Soumission de formulaire
         */
        $client->submit($form);
        $this->assertResponseRedirects("/login");
        /**
         * Suivie de la redirection et affichage de la page rendu
         */
        $client->followRedirect();
        $this->assertSelectorExists(".alert.alert-danger");
    }

    public function testSuccessfulLogin(){

        $client = static::createClient();

        /* -first methode
        $crawler = $client->request("GET", "/login");
        $form = $crawler->selectButton("Se connecter")->form([
           "email"=> "user1@gmail.com",
           "password"=>"000"
        ]);

        $client->submit($form);
       */

        /* seconde mehtode */

        $this->loadFixtureFiles([__DIR__.'/UserSecurityTestFixtures.yaml']);
        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $client->request("POST", "/login", [
            "email"=> "issam@gmail.com",
            "password"=>"000",
            "_csrf_token" =>$csrfToken
        ]);

        $this->assertResponseRedirects("/auth");

    }

    /*public function testLogout(){

        $this->expectException();
        $client = static::createClient();
        $client->request("GET", "/logout");
    }*/
}