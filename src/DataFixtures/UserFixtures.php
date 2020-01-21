<?php
/**
 * Created by PhpStorm.
 */

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture{

    public function load(ObjectManager $manager)
    {

        for($i = 0 ; $i < 10; $i++){

            $user = (new User())
                ->setEmail("user".$i."@gmail.com")
                ->setPassword("000");
            $manager->persist($user);

        }

        $manager->flush();
    }
}