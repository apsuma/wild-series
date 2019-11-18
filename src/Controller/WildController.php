<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/wild/show/{slug<[a-z0-9 ]>?}", name="wild_show", requirements={"slug"="^[a-z0-9-]+$"}, defaults={"slug"=null})
     */
    public function show(?string $slug) :Response
    {
        if (!isset($slug)) {
            $sentence = "Aucune série n'est sélectionnée, veuillez choisir une série.";
            return $this->render('wild/show.html.twig', ['slug' => $sentence]);
        } else {
            $newSentence = explode('-', $slug);
            $newSentence = implode(' ', $newSentence);
            $newSentence = ucwords($newSentence);
            return $this->render('wild/show.html.twig', ['slug' => $newSentence]);
        }
    }
}
