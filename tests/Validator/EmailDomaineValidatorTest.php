<?php

namespace App\Tests\Validator;


use App\Repository\ConfigRepository;
use App\Validator\EmailDomaine;
use App\Validator\EmailDomaineValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class EmailDomaineValidatorTest extends TestCase
{
    public function getValidator($expectViolation = false, $blockedDomaine = []){

        $configRepository = $this->getMockBuilder(ConfigRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $configRepository
            ->expects($this->any())
            ->method("dbAsArray")
            ->with("blocked_domain")
            ->willReturn($blockedDomaine);

        $emailValidator = new EmailDomaineValidator($configRepository);
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();

        if($expectViolation){

            $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
            $violation
                ->expects($this->any())
                ->method('setParameter')
                ->willReturn($violation);
            $violation->expects($this->once())->method('addViolation');

            $context
                ->expects($this->once())
                ->method("buildViolation")->willReturn($violation);
        }else{

            $context
                ->expects($this->never())
                ->method("buildViolation");

        }

        $emailValidator->initialize($context);
        return $emailValidator;

    }

    public function testCatchBadDomains(){

        $constraint = new EmailDomaine(["blocked"=>["baddomaine.com"]]);

        $this->getValidator(true)->validate("email@baddomaine.com",$constraint);

    }

    public function testAcceptGoodDomains(){

        $constraint = new EmailDomaine(["blocked"=>["baddomaine.com"]]);

        $this->getValidator(false)->validate("issam@gooddomain.com",$constraint);

    }

    public function testBlockedDomainsFromDB(){

        $constraint = new EmailDomaine(["blocked"=>["baddomaine.com"]]);

        $this->getValidator(true,["baddomaineDb.com"])->validate("issam@baddomaineDb.com",$constraint);

    }

    public function testAcceptedDomainsFromDB(){

        $constraint = new EmailDomaine(["blocked"=>["baddomaine.com"]]);

        $this->getValidator(false,["baddomaineDb.com"])->validate("issam@ba.com",$constraint);

    }
}