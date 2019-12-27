<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //création Writer1 premier abonné
        $subscriber = new User();
        $subscriber->setEmail('subscriber@monsite.com');
        $subscriber->setUsername('Writer1');
        $subscriber->setBio("I'm writing about movies");
        $subscriber->setRoles(['ROLE_SUBSCRIBER']);
        $subscriber->setPassword($this->passwordEncoder->encodePassword(
            $subscriber,
            'subscriberpwd'
        ));

        $manager->persist($subscriber);
        //création Admin1
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setUsername('Admin1');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpwd'
        ));

        $manager->persist($admin);
//sauvergarde des deux nouveaux utilisateurs
        $manager->flush();
    }
}
