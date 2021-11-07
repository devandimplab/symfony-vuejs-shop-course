<?php

namespace App\Controller\Main;

use App\Repository\CartRepository;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="main_cart_show")
     */
    public function show(Request $request, CartRepository $cartRepository): Response
    {
        $cartToken = $request->cookies->get('CART_TOKEN');
        $cart = $cartRepository->findOneBy(['token' => $cartToken]);

        return $this->render('main/cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/cart/create", name="main_cart_create")
     */
    public function create(Request $request, OrderManager $orderManager): Response
    {
        $cartToken = $request->cookies->get('CART_TOKEN');
        $user = $this->getUser();

        $orderManager->createOrderFromCartByToken($cartToken, $user);

        $redirectUrl = $this->generateUrl('main_cart_show');

        // Пример удаления куки 'CART_TOKEN' через контроллер
        $response = new RedirectResponse($redirectUrl);
        $response->headers->clearCookie('CART_TOKEN', '/', null);

        return $response;
    }
}
