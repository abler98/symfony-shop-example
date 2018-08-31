<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/", name="category_index", methods="GET")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', ['categories' => $this->categoryRepository->findAll()]);
    }

    /**
     * @Route("/{slug}", name="category_show", methods="GET")
     *
     * @param string $slug
     * @return Response
     */
    public function show(string $slug): Response
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (null === $category) {
            throw $this->createNotFoundException();
        }

        return $this->render('category/show.html.twig', [
            'category' => $category, 'products' => $category->getProducts(),
        ]);
    }
}
