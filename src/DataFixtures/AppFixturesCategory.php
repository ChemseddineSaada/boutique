<?php

namespace App\DataFixtures;

use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AppFixturesCategory extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr-FR');

        $category = new Category();
        
        $category->setName('Femme');
        $category->setDescription('Vêtements pour femme');
       
        $manager->persist($category);
        $manager->flush();

        return $category;
    }
    public function getOrder()
    {
        return 1;
    }
}
