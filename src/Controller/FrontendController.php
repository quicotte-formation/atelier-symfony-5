<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
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
