<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encode;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encode = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        // $product = new Product();
        //
        $user = new User();
        $user->setEmail('usertest@email.com');
        $encoded = $this->encode->encodePassword($user, "admin1234+");
        $user->setPassword($encoded);
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);
        $manager->flush();

    }
}
