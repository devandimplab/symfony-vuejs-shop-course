<?php

namespace App\Controller\Main;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{uuid}", name="main_product_show")
     * @Route("/product", name="main_product_show_blank")
     */
    public function show(Product $product = null): Response
    {
        if (!$product) {
            throw new NotFoundHttpException();
        }

        return $this->render('main/product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
