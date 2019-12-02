<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ActorRepository $actorRepository):Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $actorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{name}", name="details")
     */
    public function show(Actor $actor):Response
    {
        if (!$actor) {
            throw $this
                ->createNotFoundException('No parameter has been sent to find an actor');
        }
        return $this->render('actor/show.html.twig', ['actor'=>$actor]);
    }
}
