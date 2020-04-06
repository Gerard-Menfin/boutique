<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProduitFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(50, "produit", function($num)
        {
            $produit = new Produit;
            $produit->setTitre("produit$num");
            $produit->setReference("prod$num");
            $categorie = $this->faker->randomElement(["pull", "chemise", "pantalon", "t-shirt"]);
            $produit->setCategorie($categorie);
            $produit->setPrix($this->faker->randomFloat(2, 1, 190));
            $produit->setStock($this->faker->randomNumber(3));
            $produit->setPhoto($categorie . $this->faker->randomElement([0, 1, 2, 3, 4 ]) . ".jpg");
            return $produit;
        });

        $manager->flush();
    }
}
