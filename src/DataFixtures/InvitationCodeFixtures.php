<?php

namespace App\DataFixtures;


use App\Entity\InvitationCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InvitationCodeFixtures extends Fixture {


    public function load(ObjectManager $manager)
    {
        $invitationCode1 = (new InvitationCode())
        ->setCode("12345")
        ->setDescription("bla bla bla")
        ->setExpireAt( new \DateTime());

        $invitationCode2 = (new InvitationCode())
            ->setCode("54321")
            ->setDescription("bla bla bla")
            ->setExpireAt( new \DateTime());

        $manager->persist($invitationCode1);
        $manager->persist($invitationCode2);

        $manager->flush();
    }
}