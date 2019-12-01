<?php


namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name = "addCategory")
     * @return Response A response instance
     */
    public function add()
    {
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category
        );
        return $this->render('category/addCategory.html.twig',[
            'category'=> $category,
            'form'=> $form->createView(),
        ]);
    }
}