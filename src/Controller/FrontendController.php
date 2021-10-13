<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
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

        $dql = "SELECT  p 
                FROM    App:Produit p
                        JOIN p.categorie c
                WHERE   c.id=:IDCAT
                ORDER BY p.titre
                ";
        $query = $entityManager->createQuery($dql);
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
     * @Route ("/visitor/contact/a/b/c", name="contact")
     * @return Response
     */
    public function contact(){

        return $this->render("frontend/contact.html.twig");
    }

    /**
     * @Route("/", name="frontend")
     */
    public function index(): Response
    {
        return $this->render('frontend/index.html.twig',
        [
            'controller_name' => 'coucou',
        ]);
    }
}
