<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use App\Form\ActorType;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Actor $actor
     * @return Response
     */
    public function add(Request $request, Slugify $slugify):Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $entityManager->persist($actor);
            $entityManager->flush();
            return $this->redirectToRoute('actor_index');
        }
        return $this->render('actor/new.html.twig', [
           'actor'=>$actor,
           'form'=>$form->CreateView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="details")
     * @param Actor $actor
     * @return Response
     */
    public function show(Actor $actor):Response
    {
        if (!$actor) {
            throw $this
                ->createNotFoundException('No parameter has been sent to find an actor');
        }
        return $this->render('actor/show.html.twig', ['actor'=>$actor]);
    }

    /**
     * @Route("/{slug}/edit", name="details_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actor $actor, Slugify $slugify): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $entityManager->persist($actor);
            $entityManager->flush();
            return $this->redirectToRoute('actor_index');
        }
        return $this->render('actor/edit.html.twig', [
            'actor'=>$actor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="details_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Actor $actor):Response
    {
        if ($this->isCsrfTokenValid('delete' . $actor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actor_index');
    }
}
