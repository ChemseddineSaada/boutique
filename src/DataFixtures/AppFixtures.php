<?php

namespace App\DataFixtures;

use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\{Product,Category};
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AppFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr-FR');

        $product = new Product();

        $product->setName($faker->name);
        $product->setDescription($faker->text);
        $product->setPublishedAt(new \DateTime('now'));
        $product->setPrice($faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 999));
        $product->setRef($faker->ean8);

        $r=rand(0,1);
        $status=['publié','brouillon'];
        $r1=rand(0,1);
        $code=['normale','solde'];
        $r2=rand(0,4);
        $size=['xs','s', 'm','l','xl'];

        $product->setStatus($status[$r]);
        $product->setCode($code[$r1]);
        $product->setSize($size[$r2]);

        $categories=$manager->getRepository(Category::Class)->findAll();
        
;       $rc = rand(0,count($categories));
        $product->setCategory($categories[$rc]);
       
        $manager->persist($product);
        $manager->flush();

        return $product;
    }
    public function getOrder()
    {
        return 3;
    }
}
