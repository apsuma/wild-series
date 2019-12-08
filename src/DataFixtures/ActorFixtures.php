<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

// pour appel des class dans le bon ordre (Program avant Actor), implements DependentFixtureInterface
class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = ['andrew-lincoln', 'norman-reedus', 'lauren-cohan', 'danai-gurira'];

    public function load(ObjectManager $manager)
    {
        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);
            $actor->addProgram($this->getReference('program_0'));
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