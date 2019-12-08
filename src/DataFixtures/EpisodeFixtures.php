<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 120; $i++) {
            $episode = new Episode();
            $episode->setNumber($faker->numberBetween(1, 25));
            $episode->setTitle($faker->sentence($nbWords = 6, $variableNbWords=true));
            $episode->setSynopsis($faker->text);
            $ref = 'season_'.rand(0,49);
            $episode->setSeason($this->getReference($ref));
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ SeasonFixtures::class];
    }
}