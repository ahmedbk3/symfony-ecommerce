<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class CartService
{
    private $session;
    private $em;
    private $security;
    private $cartRepo;
    private $productRepo;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $em,
        Security $security,
        CartRepository $cartRepo,
        ProductRepository $productRepo
    ) {
        $this->session = $requestStack->getSession();
        $this->em = $em;
        $this->security = $security;
        $this->cartRepo = $cartRepo;
        $this->productRepo = $productRepo;
    }

 public function getCart(): ?Cart
    {
        $user = $this->security->getUser();

        if ($user) {
            $cart = $this->cartRepo->findOneBy(['user' => $user]);
            if (!$cart) {
                $cart = new Cart();
                $cart->setUser($user);
                $cart->setCreatedAt(new \DateTimeImmutable());
                $this->em->persist($cart);
                $this->em->flush();
            }
            return $cart;
        }

        // If guest, don't return a Cart entity (use session logic elsewhere)
        return null;
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $product = $this->productRepo->find($productId);
        $user = $this->security->getUser();

        if ($user) {
            $cart = $this->getCart();

            foreach ($cart->getCartItems() as $item) {
                if ($item->getProduct()->getId() === $productId) {
                    $item->setQuantity($item->getQuantity() + $quantity);
                    $this->em->flush();
                    return;
                }
            }

            $item = new CartItem();
            $item->setProduct($product);
            $item->setQuantity($quantity);
            $item->setCart($cart);
            $this->em->persist($item);
            $this->em->flush();
        } else {
            $cart = $this->session->get('cart', []);
            $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
            $this->session->set('cart', $cart);
        }
    }

    public function update(int $productId, int $quantity): void
    {
        $user = $this->security->getUser();

        if ($user) {
            $cart = $this->getCart();
            foreach ($cart->getCartItems() as $item) {
                if ($item->getProduct()->getId() === $productId) {
                    if ($quantity > 0) {
                        $item->setQuantity($quantity);
                    } else {
                        $cart->removeCartItem($item);
                        $this->em->remove($item);
                    }
                    $this->em->flush();
                    return;
                }
            }
        } else {
            $cart = $this->session->get('cart', []);
            if ($quantity > 0) {
                $cart[$productId] = $quantity;
            } else {
                unset($cart[$productId]);
            }
            $this->session->set('cart', $cart);
        }
    }

    public function remove(int $productId): void
    {
        $this->update($productId, 0);
    }

    public function getItems(): array
    {
        $user = $this->security->getUser();

        if ($user) {
            $cart = $this->getCart();
            return $cart ? $cart->getCartItems()->toArray() : [];
        } else {
            $cartData = $this->session->get('cart', []);
            $items = [];

            foreach ($cartData as $productId => $quantity) {
                $product = $this->productRepo->find($productId);
                if ($product) {
                    $item = new CartItem();
                    $item->setProduct($product);
                    $item->setQuantity($quantity);
                    $items[] = $item;
                }
            }

            return $items;
        }
    }

    public function getTotal(): float
    {
        $items = $this->getItems();
        $total = 0;

        foreach ($items as $item) {
            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        return $total;
    }
}
