<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request) :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if(!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        $form = $this->createForm(ProgramSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            return $this->showByCategory($data['searchField']);
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
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
    public function showByProgram(string $programName):?Response
    {
        if (!$programName) {
            throw $this
                ->createNotFoundException(('No parameter has been sent to find a program'));
        }
        $programName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($programName)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($programName)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $programName . ', found in program\'s table.'
            );
        }
        $seasons = $program->getSeasons();
        return $this->render('wild/program.html.twig', [
            'programName'=>$programName,
            'program'=>$program,
            'seasons'=>$seasons,
        ]);
    }

    /**
     * @Route("/season/{id}", name="season_details", defaults={"id"= null})
     * @return Response
     */
    public function showBySeason(Season $season):Response
    {
        if (!$season) {
            throw $this
                ->createNotFoundException('No parameter has been sent to find a season');
        }
        return $this->render('wild/season.html.twig', [
            'season' => $season,
        ]);
    }

    /**
     * @route("/season/{season}/episode/{episode}", name="season_episode_details")
     * @return Response
     */
    public function showEpisode(Season $season, Episode $episode):Response
    {
        if (!$episode) {
            throw $this
                ->createNotFoundException('No parameter has been sent to find an episode');
        }
        $programName =  mb_strtolower(trim($season->getProgram()->getTitle()));
        $programName = str_replace(' ', '-', $programName);
        return $this->render('wild/episode.html.twig', [
            'season'=>$season,
            'episode'=>$episode,
            'programName'=>$programName,
        ]);
    }
}

