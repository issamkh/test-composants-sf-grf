<?php

namespace App\Validator;

use App\Repository\ConfigRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailDomaineValidator extends ConstraintValidator
{
    private $configRepository;
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\EmailDomaine */

        if (null === $value || '' === $value) {
            return;
        }

        $domain = substr($value, strpos($value , "@") + 1);
        $blockDomains = array_merge($constraint->blocked , $this->configRepository->dbAsArray("blocked_domain"));

        if(in_array($domain,$blockDomains)  ){

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

    }

}
