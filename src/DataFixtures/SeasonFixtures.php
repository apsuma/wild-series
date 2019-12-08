<?php


namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;


class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $season = new Season();
            $season->setYear($faker->numberBetween(1995, 2019));
            $season->setDescription($faker->sentence($nbWords = 6, $variableNbWords=true));
            $name = 'program_'. rand(0,4);
            $season->setProgram($this->getReference($name));
            $manager->persist($season);
            $this->addReference('season_'.$i, $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ ProgramFixtures::class];
    }
}