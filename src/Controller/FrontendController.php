<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Entity\Produit;
use App\Form\ContactType;
use App\Form\SuggererProduitType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    /**
     * @Route ("/suggerer-produit", name="suggerer-produit")
     */
    public function suggererProduit(Request $request, EntityManagerInterface $entityManager){

        $produit = new Produit();
        $form = $this->createForm(SuggererProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute("home");
        }

        return $this->renderForm("frontend/suggerer-produit.html.twig", ['monForm'=>$form] );
    }

    /**
     * @Route("/lister-produits-par-annee/{annee}", name="lister-produits-par-annee")
     */
    public function listerProduitsParAnnee($annee, ProduitRepository $produitRepository){

        $produits = $produitRepository->findBy(['anneeSortie'=>$annee], ['titre'=>'ASC']);

        return $this->render("frontend/lister-produits.html.twig",
            ['produitsTrouves'=>$produits]);
    }

    /**
     * @Route("/lister-annees", name="lister-annees")
     */
    public function listerAnnees(EntityManagerInterface $entityManager){

        $dql = "SELECT  p.anneeSortie, COUNT(p) nb_prod 
                FROM    App:Produit p
                GROUP BY p.anneeSortie 
                ORDER BY p.anneeSortie";
        $donnees = $entityManager->createQuery($dql)->getResult();

        return $this->render("frontend/lister-annees.html.twig",
            ['lesDonnees'=>$donnees]);
    }

    /**
     * @Route("/lister-produits/{idCat}", name="lister-produits")
     */
    public function listerProduits($idCat, EntityManagerInterface $entityManager){

        $qb = $entityManager->createQueryBuilder();
        $qb->select("p");
        $qb->from("App:Produit", "p")
            ->join("p.categorie", "c")
            ->where(" c.id=:IDCAT")
            ->orderBy("p.titre", "ASC");
        $query = $qb->getQuery();
/*
        $dql = "SELECT  p 
                FROM    App:Produit p
                        JOIN p.categorie c
                WHERE   c.id=:IDCAT
                ORDER BY p.titre
                ";
        $query = $entityManager->createQuery($dql);
*/
        $query->setParameter('IDCAT', $idCat);
        $produits = $query->getResult();

        return $this->render("frontend/lister-produits.html.twig",
            ['produitsTrouves'=>$produits]);
    }

    /**
     * @Route("/lister-categories", name="lister-categories")
     */
    public function listerCategories(CategorieRepository $categorieRepository){

        $categories = $categorieRepository->findAll();
        dump($categories);

        return $this->render("frontend/lister-categories.html.twig",
            ['lesCategories'=>$categories]);
    }

    /**
     * @Route ("/ajouter-produit", name="ajouter-produit")
     */
    public function ajouterProduit(EntityManagerInterface $entityManager){

        //$entityManager->

        $entityManager->flush();// COMMIT TTS OP.

        return $this->render('frontend/ajouter-produit.html.twig');
    }

    /**
     * @Route ("/faq", name="faq")
     * @return Response
     */
    public function frequentlyAskedQuestions(){

        return $this->render("frontend/faq.html.twig");
    }

    /**
     * @Route ("/contact", name="contact")
     * @return Response
     */
    public function contact(Request $request){

        $data = new ContactDTO();
        $monForm = $this->createForm(ContactType::class, $data);
        $monForm->handleRequest($request);

        if( $monForm->isSubmitted() && $monForm->isValid() ){

            // Envoi email
            dump("Envoi email");
            return $this->redirectToRoute("home");
        }

        return $this->renderForm("frontend/contact.html.twig", ['formulaire'=>$monForm]);
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('frontend/index.html.twig',
        [
            'controller_name' => 'coucou',
        ]);
    }
}
