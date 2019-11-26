<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/wild", name="wild_")
     */
class WildController extends AbstractController
{
    /**
     * @Route("/", name = "index")
     * @return Response A response instance
     */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if(!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/show/{slug<^[a-z0-9-]+$>}", name="show", defaults={"slug" = null})
     * @return Response
     */
    public function show(?string $slug) :Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table .');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/category/{categoryName<^[a-z0-9-]+$>}", name="show_category", defaults={"categoryName" = null})
     * @return Response
     */
    public function showByCategory(string $categoryName):Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException(('No parameter has been sent to find a category'));
        }
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with ' . $categoryName . ', found in category\'s table.'
            );
        }
        $id = $category->getId();
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findByCategory($id);
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program with '.$categoryName.', found in program\'s table.'
            );
        }
        return $this->render('wild/category.html.twig', [
            'programs'=> $programs,
            'categoryName' => $categoryName,
        ]);
    }

    /**
     * @Route("/program/{programName<^[a-z0-9-]+$>}", name="show_program", defaults={"programName" = null})
     * @return Response
     */
    public function showByProgram($programName)
    {
        return $this->render('wild/program.html.twig');
    }

    /**
     * @Route("/season/{seasonId<^[0-9]+$>}", name="show_season", defaults={"seasonId"= null})
     * @return Response
     */
    public function showBySeason($seasonId)
    {
        return $this->render('wild/season.html.twig');
    }
}

