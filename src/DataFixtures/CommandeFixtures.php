<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Commande;
use Doctrine\Common\DataFixtures\DependentFixtureInterface as Dependent;

class CommandeFixtures extends BaseFixture implements Dependent
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(20, "commande", function($num){
            $commande = new Commande;
            $commande->setMontant($this->faker->randomFloat(2, 100, 5000));
            $commande->setDateEnregistrement($this->faker->dateTime("now"));
            $commande->setEtat($this->faker->randomElement(["en cours", "en attente", "livrée"]));
            $commande->setMembre($this->getRandomReference("membre"));
            return $commande;
        });
        $manager->flush();
    }

    public function getDependencies(){
        // j'indique les fixtures qui doivent être lancées avant la fixture actuelle
        return [ MembreFixtures::class ];
    }
}
