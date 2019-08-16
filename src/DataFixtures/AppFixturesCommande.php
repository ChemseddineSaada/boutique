<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\{Commande,Product,Client};
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AppFixturesCommande extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $commande = new Commande();

        $products = $manager->getRepository(Product::Class)->findAll();
        $clients = $manager->getRepository(Client::Class)->findAll();

        $rp=rand(0,count($products)-1);
        $rc=rand(0,count($clients)-1);

        $commande->setProduct($products[$rp]);
        $commande->setClient($clients[$rc]);

        $manager->persist($commande);
        $manager->flush();  

        return $commande;
    }
    public function getOrder()
    {
        return 1;
    }
}
