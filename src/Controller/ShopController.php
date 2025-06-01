<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ShopController extends AbstractController
{
    #[Route('/products', name: 'shop_products')]
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        $name = $request->query->get('name');
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');

        $minPrice = is_numeric($minPrice) ? (float) $minPrice : null;
        $maxPrice = is_numeric($maxPrice) ? (float) $maxPrice : null;

        $sortBy = $request->query->get('sort_by');
        $order = $request->query->get('order', 'asc');

        $queryBuilder = $productRepository->getFilteredQueryBuilder($name, $minPrice, $maxPrice, $sortBy, $order);

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // Current page
            3 // Items per page
        );

        return $this->render('shop/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
