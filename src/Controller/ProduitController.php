<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
//use App\Entity\Categorie;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(ProduitRepository $repository)
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',

        ]);
    }

    /**
     * @Route("/produit/ajouter", name="produit_ajouter")
     */
    public function ajouter(Produit $produit=null, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // cas où le formulaire d'ajout a été soumis par l'utilisateur et est valide
            $produit = $form->getData();
            // on met à jour la base de données
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le produit ' . $produit->getLibelle() . ' a été ajouté.'
            );
            return $this->redirectToRoute('produit');
        } else {
            // cas où l'utilisateur a demandé l'ajout, onaffiche le formulaire d'ajout
            return $this->render('produit/ajouter.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    }

    /**
     * @Route("/produit/modifier/{id<\d+>}", name="produit_modifier")
     */
    public function modifier(Produit $produit = null, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // cas où le formulaire  a été soumis par l'utilisateur et est valide
            //pas besoin de "persister" l'entité : en effet, l'objet a déjà été retrouvé à partir de Doctrine ORM.
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le produit '.$produit->getLibelle().' a été modifié.'
            );

            return $this->redirectToRoute('produit');
        }
        // cas où l'utilisateur a demandé la modification, on affiche le formulaire pour la modification
        return $this->render('produit/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/supprimer/{id<\d+>}", name="produit_supprimer")
     */
    public function supprimer(Produit $produit, Request $request, EntityManagerInterface $entityManager)
    {
        if ($this->isCsrfTokenValid('action-item'.$produit->getId(), $request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le produit '.$produit->getLibelle().' a été supprimé.'
            );

            return $this->redirectToRoute('produit');
        }
    }


}
