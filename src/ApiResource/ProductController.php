<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\Get;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Get(
    uriTemplate: '/products',
    controller: 'App\ApiResource\ProductController::list',
    name: 'api_products'
)]
#[Get(
    uriTemplate: '/products/{id}',
    controller: 'App\ApiResource\ProductController::get',
    name: 'api_product'
)]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    public function list(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $search = $request->query->get('search', '');
        $locale = $request->query->get('locale', 'en');

        $products = $productRepository->findBySearchPaginated($search, $page, $limit, $locale);
        $totalProducts = $productRepository->countBySearch($search, $locale);

        return $this->json([
            'products' => $products,
            'totalProducts' => $totalProducts,
            'currentPage' => $page,
            'totalPages' => ceil($totalProducts / $limit),
        ]);
    }

    public function get(Product $product, ProductRepository $productRepository): JsonResponse
    {
        return $this->json($productRepository->getProductData($product));
    }
}
