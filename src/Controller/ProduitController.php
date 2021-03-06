<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/admin/produit", name="produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/produit/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Je récupère les informations du fichier uploadé
            $photoUploade = $form->get("photo")->getData();

            // Je récupère le nom du fichier uploadé 
            $nomPhoto = pathinfo($photoUploade->getClientOriginalName(), PATHINFO_FILENAME);

            // Je remplace les espaces dans le nom du fichier
            $nomPhoto = str_replace(" ", "_", $nomPhoto);

            // Je rajoute un string unique (pour éviter les fichiers doublons) et l'extension du fichier téléchargé
            $nomPhoto .= uniqid() . "." . $photoUploade->guessExtension();

            // J'enregistre le fichier uploadé sur mon serveur, dans le dossier public/images
            $photoUploade->move("images", $nomPhoto);

            // Pour enregistrer l'information en BDD :
            $produit->setPhoto($nomPhoto);
            
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/produit/{id}", name="produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/admin/produit/{id}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get("photo")->getData()){
                // Je récupère les informations du fichier uploadé
                $photoUploade = $form->get("photo")->getData();

                // Je récupère le nom du fichier uploadé 
                $nomPhoto = pathinfo($photoUploade->getClientOriginalName(), PATHINFO_FILENAME);

                // Je remplace les espaces dans le nom du fichier
                $nomPhoto = str_replace(" ", "_", $nomPhoto);

                // Je rajoute un string unique (pour éviter les fichiers doublons) et l'extension du fichier téléchargé
                $nomPhoto .= uniqid() . "." . $photoUploade->guessExtension();

                // J'enregistre le fichier uploadé sur mon serveur, dans le dossier public/images
                $photoUploade->move("images", $nomPhoto);

                // Pour enregistrer l'information en BDD :
                $produit->setPhoto($nomPhoto);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/produit/{id}", name="produit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index');
    }

    /**
     * @Route("/produit/{id}", name="produit_fiche", methods={"GET"})
     */
    public function ficheProduit(Produit $produit): Response
    {
        return $this->render('produit/fiche.html.twig', [
            'produit' => $produit,
        ]);
    }


}
