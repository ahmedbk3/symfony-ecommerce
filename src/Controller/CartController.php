<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getItems(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(Product $product, Request $request, CartService $cartService): Response
    {
        $quantity = (int) $request->request->get('quantity', 1);
        $cartService->add($product->getId(), $quantity);
        
        return $this->redirectToRoute('cart_index'); // Or back to previous page
    }

    #[Route('/cart/update/{id}', name: 'cart_update', methods: ['POST'])]
    public function update(Request $request, Product $product, CartService $cartService): RedirectResponse
    {
        $quantity = (int) $request->request->get('quantity', 1);
        $cartService->update($product->getId(), $quantity);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(Product $product, CartService $cartService): RedirectResponse
    {
        $cartService->remove($product->getId());
        return $this->redirectToRoute('cart_index');
    }
}

