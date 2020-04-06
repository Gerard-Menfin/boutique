<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $pr)
    {
        // Comment récupérer la liste de tous les produits ?
        
        //1ere méthode
        //$liste_produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        
        //2ième méthode : 
        $liste_produits = $pr->findAll();
        

        return $this->render('home/index.html.twig', [ "liste" => $liste_produits ]);
    }

    /**
     * @Route("/recherche", name="recherche")
     */
    public function recherche(ProduitRepository $pr, Request $request)
    {
        $motRecherche = $request->query->get("recherche");
        if($motRecherche){
            // $liste_produits = $pr->findBy([ "titre" => $motRecherche]);
            //$pr->findBy([ "titre" => $motRecherche ]) ==> SELECT * FROM produit WHERE titre = '$motRecherche'
            // findBy ne trouve que des égalités donc pas de LIKE
            $liste_produits = $pr->findByTitreCategorieDescription($motRecherche);
        } else {
            $liste_produits = [];
        }
            
        return $this->render('home/index.html.twig', [ "liste" => $liste_produits, "mot_recherche" => $motRecherche ]);
    }


    /**
     * @Route("/test", name="test")
     * @IsGranted("ROLE_DEV")
     */
    public function test()
    {
            return $this->render("test.html.twig");
    }
}
