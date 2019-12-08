<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

// pour appel des class dans le bon ordre (Program avant Actor), implements DependentFixtureInterface
class ActorFixtures extends Fixture implements DependentFixtureInterface
{
        public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $name = 'program_'. rand(0,4);
            $actor->addProgram($this->getReference($name));
            $manager->persist($actor);
        }
        $manager->flush();
    }

    // programFixtures devient une dépendance de ActorFixtures
    public function getDependencies()
    {
        return [ ProgramFixtures::class];
    }
}