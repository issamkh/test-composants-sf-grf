<?php
/**
 * Created by PhpStorm.
 */

namespace App\Tests\Repository;


use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{

    use FixturesTrait;
    public function testCountUsers(){

        self::bootKernel();
        //$this->loadFixtures([UserFixtures::class]);
        $this->loadFixtureFiles([__DIR__.'/UserRepositoryTestFixtures.yaml']);

        $users = self::$container->get(UserRepository::class)->count([]);

        $this->assertEquals(10 , $users);

    }

}