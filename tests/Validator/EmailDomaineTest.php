<?php


namespace App\Tests\Validator;


use App\Validator\EmailDomaine;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class EmailDomaineTest extends  TestCase
{

    public function testRequiredParameters(){

        $this->expectException(MissingOptionsException::class);
        new EmailDomaine();
    }

    public function testBlockedParameter(){

        $this->expectException(ConstraintDefinitionException::class);

        new EmailDomaine(["blocked"=>"email"]);
    }

    public function testValidDomaine(){


        $domaine = new EmailDomaine(["blocked" => ["issam", "amine"]]);
        $this->assertEquals($domaine->blocked , ["issam", "amine"]);


    }
}