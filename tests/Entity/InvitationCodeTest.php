<?php


namespace App\Tests\Entity;

use App\DataFixtures\InvitationCodeFixtures;
use App\Entity\InvitationCode;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class InvitationCodeTest extends KernelTestCase
{
    use FixturesTrait;

    function getEntity(): InvitationCode{

        return  (new InvitationCode())
            ->setCode("12345")
            ->setDescription("hhhh")
            ->setExpireAt(new \DateTime());
    }

    public function assertHasError(InvitationCode $code , $numberError = 0){

        self::bootKernel();
        $errors = self::$container->get('validator')->validate($code);
        $message = [];

        /** @var ConstraintViolation $error **/
        foreach ($errors as $error){

            $message[] = $error->getPropertyPath() ." => " .$error->getMessage();
        }

        $this->assertCount($numberError , $errors, implode(",", $message));

    }

    function testValideEntity(){

        $code = $this->getEntity();
        $code->setCode("12345");
        $this->assertHasError($code, 0);

    }

    function testInvalideEntity(){

        $code = $this->getEntity();
        $code->setCode("123");
        $this->assertHasError($code,1);
    }

    public function testInvalidBlankCode(){

        $code = $this->getEntity();
        $code->setCode("");
        $this->assertHasError($code,1);
    }

    public function testInvalidBlankDescription(){

        $code = $this->getEntity();
        $code->setDescription("");
        $this->assertHasError($code,1);
    }

    public function testInvalidUsedCode(){

        $this->loadFixtures([InvitationCodeFixtures::class]);
        $code = $this->getEntity();
        $code->setCode("54321");
        $this->assertHasError($code,1);
    }

}