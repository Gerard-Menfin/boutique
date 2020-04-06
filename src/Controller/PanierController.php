<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier") 
     */
    public function index(Session $session)
    {
        $panier = $session->get("panier");
        return $this->render('panier/index.html.twig', [ "panier" => $panier ]);
    }

    /**
     * @Route("/ajouter-panier/{id}", name="ajouter_panier")
     */
    public function ajouterPanier(Session $session, Request $request, ProduitRepository $produitRepository, $id){
        // EXO : quand j'ajoute un produit qui existe déjà dans le panier, je change la quantité de ce produit
        // au lieu de rajouter une ligne
        
        $produitAajouter = $produitRepository->find($id);
        $panier = $session->get("panier", []);
        $qte = $request->query->get("qte");
        $qte = empty($qte) ? 1 : $qte;
        $existe = false;
        foreach($panier as $indice => $ligne){
            if($produitAajouter->getId() == $ligne["produit"]->getId()){
                $qte += $ligne["qte"];
                $panier[$indice] = [ "produit" => $produitAajouter, "qte" => $qte ];
                $existe = true;
            }
        }
        if(!$existe){
            $panier[] = [ "produit" => $produitAajouter, "qte" => $qte ];
        }
        $this->addFlash("success", "Le produit a été ajouté dans votre panier");
        $session->set("panier", $panier);
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/vider", name="vider_panier")
     */
    public function viderPanier(Session $session){
        $session->remove("panier");
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/supprimer-produit/{id}", name="supprimer_produit_panier")
     */
    public function supprimerProduit(Session $session, $id){
        $panier = $session->get("panier");
        foreach($panier as $indice => $ligne){
            if($ligne["produit"]->getId() == $id){
                unset($panier[$indice]);
                break;
            }
        }
        $session->set("panier", $panier);
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/modifier-produit/{id}", name="modifier_produit_panier")
     */
    public function modifierQuantite(Session $session, Request $request, $id){
        $panier = $session->get("panier");
        $qte = $request->query->get("qte");
        foreach($panier as $indice => $ligne){
            if($ligne["produit"]->getId() == $id){
                $panier[$indice]["qte"] = $qte;
                break;
            }
        }
        $session->set("panier", $panier);
        return $this->redirectToRoute("panier");
    }





}
