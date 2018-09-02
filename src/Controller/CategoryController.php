<?php

namespace App\Controller;

use App\Entity\Product;
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
        return $this->render('category/index.html.twig', [
            'categories' => $this->categoryRepository->findAllWithLastProducts(),
        ]);
    }

    /**
     * @Route(
     *     "/{slug}/{page}",
     *     name="category_show",
     *     methods="GET",
     *     requirements={"page"="\d+"},
     *     defaults={"page"=1}
     * )
     *
     * @param string $slug
     * @param int $page
     * @return Response
     */
    public function show(string $slug, int $page): Response
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (null === $category) {
            throw $this->createNotFoundException();
        }

        $pagination = $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getManager()->getRepository(Product::class)->getQueryForCategory($category),
            $page, Product::PER_PAGE
        );

        return $this->render('category/show.html.twig', [
            'category' => $category, 'products' => $pagination,
            'currency' => $this->getParameter('currency'),
        ]);
    }
}
