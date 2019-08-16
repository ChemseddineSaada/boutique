<?php

namespace App\DataFixtures;

use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Client;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AppFixturesClient extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager) 
    {
        $faker = Faker\Factory::create('fr-FR');

        $client = new Client();
        
        $client->setEmail($faker->email);
        $client->setAddress($faker->address);
        $client->setTelephone($faker->phoneNumber);

       
        $manager->persist($client);
        $manager->flush();

        return $client;
    }
    public function getOrder()
    {
        return 2;
    }
}
