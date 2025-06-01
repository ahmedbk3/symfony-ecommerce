<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/products', name: 'shop_products')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $name = $request->query->get('name');
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');

        $minPrice = is_numeric($minPrice) ? (float) $minPrice : null;
        $maxPrice = is_numeric($maxPrice) ? (float) $maxPrice : null;

        $sortBy = $request->query->get('sort_by');
        $order = $request->query->get('order', 'asc');

        $products = $productRepository->findByFilters($name, $minPrice, $maxPrice, $sortBy, $order);
        return $this->render('shop/index.html.twig', [
            'products' => $products,
        ]);
    }
}
