<?php

namespace App\DataFixtures;

use App\Entity\Details;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface as Dependent;
use Doctrine\Common\Persistence\ObjectManager;

class DetailsFixtures extends BaseFixture implements Dependent
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany("40", "details", function ($num)
        {
            $detail_commande = new Details;
            $detail_commande->setCommande($this->getRandomReference("commande"));
            $produit = $this->getRandomReference("produit");
            $detail_commande->setProduit($produit);
            $quantite = $this->faker->randomNumber(1);
            $detail_commande->setQuantite($quantite);
            $detail_commande->setPrix($quantite * $produit->getPrix());
            return $detail_commande;
        });
        $manager->flush();
    }

    public function getDependencies(){
        return [ CommandeFixtures::class, ProduitFixtures::class ];
    }

}
