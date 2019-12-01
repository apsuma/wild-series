<?php


namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence;


/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name = "add")
     * @return Response A response instance
     */
    public function add(Request $request):Response
    {
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $categoryManager = $this->getDoctrine()->getManager();
            $categoryManager->persist($category);
            $categoryManager->flush();
            return $this->redirectToRoute('wild_index');
        }
        return $this->render('category/addCategory.html.twig',[
            'category'=> $category,
            'form'=> $form->createView(),
        ]);
    }
}