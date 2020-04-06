<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Membre;

class MembreFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $membre = new Membre;
        $membre->setEmail("admin@boutique.fr");
        $membre->setPassword(password_hash("admin", PASSWORD_DEFAULT));
        $membre->setNom("Min");
        $membre->setPrenom("Ad");
        $membre->setAdresse("rue Quelque Part");
        $membre->setCodePostal("75000");
        $membre->setVille("Paris");
        $membre->setRoles(["ROLE_ADMIN", "ROLE_MODERATEUR"]);
        $membre->setCivilite("h");
        $membre->setPseudo("Admin");
        $manager->persist($membre);

        // La méthode createMany est défini dans BaseFixture.
        // la fonction qui est passée en 3ième paramètre, renvoi l'entité créée
        // cette fonction va être executée autant de fois que vaut le 1er paramètre
        $this->createMany(10, "membre", function($num){
            $membre = new Membre;
            $membre->setEmail("membre" . $num . "@yopmail.com");
            $membre->setPassword(password_hash("membre" . $num, PASSWORD_DEFAULT));
            $membre->setNom($this->faker->lastName);
            $membre->setPrenom($this->faker->firstName);
            $membre->setAdresse($this->faker->address);
            $membre->setCodePostal(substr($this->faker->postcode, 0, 5));
            $membre->setVille(substr($this->faker->city, 0, 20));
            $membre->setRoles(["ROLE_USER"]);
            $membre->setCivilite($this->faker->randomElement(["h", "f", "a"]));
            $membre->setPseudo("membre" . $num);
            return $membre;
        });

        $manager->flush();
    }
}
