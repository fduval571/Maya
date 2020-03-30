<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AnimalController extends AbstractController
{
    /**
     * @Route("/animal", name="animal")
     * @Route("/animal/demandermodification/{id<\d+>}", name="animal_demandermodification")
     */
    public function index($id = null, AnimalRepository $repository, Request $request)
    {
        // créer l'objet et le formulaire de création
        $animal = new Animal();
        $formCreation = $this->createForm(AnimalType::class, $animal);

        // si 2e route alors $id est renseigné et on  crée le formulaire de modification
        $formModificationView = null;
        if ($id != null) {
            // sécurité supplémentaire, on vérifie le token
            if ($this->isCsrfTokenValid('action-item'.$id, $request->get('_token'))) {
                $animalModif = $repository->find($id);   // l'animal à modifier
                $formModificationView = $this->createForm(AnimalType::class, $animalModif)->createView();
            }
        }


        // lire les animaux
        $lesAnimaux = $repository->findAll();

        // rendre la vue
        return $this->render('animal/index.html.twig', [
            'formCreation' => $formCreation->CreateView(),
            'lesAnimaux' => $lesAnimaux,
            'formModification' => $formModificationView,
            'idAnimalModif' => $id,

        ]);
    }

    /**
     * @Route("/animal/ajouter", name="animal_ajouter")
     */
    public function ajouter(Animal $animal = null, Request $request, EntityManagerInterface $entityManager, AnimalRepository $repository)
    {
        //  $animal objet de la classe Animal, il contiendra les valeurs saisies dans les champs après soumission du formulaire.
        //  $request  objet avec les informations de la requête HTTP (GET, POST, ...)
        //  $entityManager  pour la persistance des données

        // création d'un formulaire de type AnimalType
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);

        // handleRequest met à jour le formulaire
        //  si le formulaire a été soumis, handleRequest renseigne les propriétés
        //      avec les données saisies par l'utilisateur et retournées par la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // c'est le cas du retour du formulaire
            //         l'objet $animal a été automatiquement "hydraté" par Doctrine
            dump($animal);
            // dire à Doctrine que l'objet sera (éventuellement) persisté
            $entityManager->persist($animal);
            // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
            $entityManager->flush();
            // ajouter un message flash de succès pour informer l'utilisateur
            $this->addFlash(
                'success',
                'L animal ' . $animal->getNom() . ' a été ajoutée.'
            );
            // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'un nouvel animal
            return $this->redirectToRoute('animal');

        } else {
// affichage de la liste des animaux avec le formulaire de création et ses erreurs
            // lire les animaux
            $lesAnimaux = $repository->findAll();
            // rendre la vue
            return $this->render('animal/index.html.twig', [
                'formCreation' => $form->createView(),
                'lesAnimaux' => $lesAnimaux,
                'formModification' => null,
                'idAnimalModif' => null,
            ]);
        }
    }

    /**
     * @Route("/aniaml/modifier/{id<\d+>}", name="animal_modifier")
     */
    public function modifier(Animal $animal = null, $id = null, Request $request, EntityManagerInterface $entityManager, AnimalRepository $repository)
    {
        //  Symfony 4 est capable de retrouver la catégorie à l'aide de Doctrine ORM directement en utilisant l'id passé dans la route
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // va effectuer la requête d'UPDATE en base de données
            // pas besoin de "persister" l'entité car l'objet a déjà été retrouvé à partir de Doctrine ORM.
            $entityManager->flush();
            $this->addFlash(
                'success',
                'L animal '.$animal->getNom().' a été modifié.'
            );
            // rediriger vers l'affichage des animaux qui comprend le formulaire pour l"ajout d'un nouvel animal
            return $this->redirectToRoute('animal');

        } else {
            // affichage de la liste des catégories avec le formulaire de modification et ses erreurs
            // créer l'objet et le formulaire de création
            $animal = new Animal();
            $formCreation = $this->createForm(AnimalType::class, $animal);
            // lire les animaux
            $lesAnimaux = $repository->findAll();
            // rendre la vue
            return $this->render('animal/index.html.twig', [
                'formCreation' => $formCreation->createView(),
                'lesAnimaux' => $lesAnimaux,
                'formModification' => $form->createView(),
                'idAnimalModif' => $id,
            ]);
        }
    }

    /**
     * @Route("/animal/supprimer/{id<\d+>}", name="animal_supprimer")
     */
    public function supprimer(Animal $animal = null, Request $request, EntityManagerInterface $entityManager)
    {
        // vérifier le token
        if ($this->isCsrfTokenValid('action-item'.$animal->getId(), $request->get('_token'))) {
            //if ($animal->getProduits()->count() > 0) {
            //    $this->addFlash(
            //        'error',
            //        'Il existe des races dans l animal ' . $animal->getNom() . ', elle ne peut pas être supprimé.'
            //    );
            //    return $this->redirectToRoute('animal');
            //}
            // supprimer la catégorie
            $entityManager->remove($animal);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'L animal ' . $animal->getNom() . ' a été supprimé.'
            );
        }
        return $this->redirectToRoute('animal');
    }


}
