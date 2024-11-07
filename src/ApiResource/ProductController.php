<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\Get;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        private ProductRepository $productRepository
    ) {}

    public function list(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $products = $productRepository->findAllPaginated($page, $limit);
        $totalProducts = $productRepository->countAll();


        return $this->json([
                               'products' => $products,
                               'totalProducts' => $totalProducts,
                               'currentPage' => $page,
                               'totalPages' => ceil($totalProducts / $limit),
                           ]);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function get(Product $product, ProductRepository $productRepository): JsonResponse
    {
        return $this->json($this->$productRepository->getProductData($product));
    }
}
