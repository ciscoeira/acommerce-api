<?php

namespace App\Controller;

use App\DBAL\Types\ProductCurrencyType;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Entity\ProductService;
use App\Service\ExchangeRateService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Parameter;
use Nelmio\ApiDocBundle\Annotation\Property;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * Creates new product
     *
     * @Route("/api/product", name="create_product", methods={"POST"})
     *
     * @param Request $request
     * @param ProductService $productService
     * @return JsonResponse
     * @throws \Exception
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(ref=@Model(type=Product::class, groups={"product_write"}))
     * )
     * @OA\Response(
     *     response=201,
     *     description="Product created",
     *     @Model(type=Product::class, groups={"product_read"})
     * )
     */
    public function createProduct(Request $request, ProductService $productService)
    {
        if ($productService->create(json_decode($request->getContent(), true))->save()) {
            return $this->json([
                'product' => $productService->getEntity()
            ], JsonResponse::HTTP_CREATED, [], [
                'groups' => ['product_read']
            ]);
        }

        return $this->json([
            'errors' => $productService->getErrors()
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * List products
     *
     * @Route("/api/product", name="list_products", methods={"GET"})
     *
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return JsonResponse
     *
     * @OA\Response(
     *     response=200,
     *     description="Product created",
     *     @Model(type=Product::class, groups={"product_read"})
     * )
     */
    public function listProducts(Request $request, ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->json([
            'products' => $products
        ], JsonResponse::HTTP_OK, [], [
            'groups' => ['product_read']
        ]);
    }

    /**
     * List featured products
     *
     * @Route("/api/product/featured", name="featured_products", methods={"GET"})
     *
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param ExchangeRateService $exchangeRateService
     * @return JsonResponse
     *
     * @OA\Parameter(
     *     name="currency",
     *     in="query",
     *     @OA\Property(property="currency", ref=@Model(type=Product::class, groups={"product_read"})),
     *     required=false
     * )
     * @OA\Response(
     *     response=200,
     *     description="Product created",
     *     @Model(type=Product::class, groups={"product_read"})
     * )
     */
    public function listFeaturedProducts(Request $request, ProductRepository $productRepository, ExchangeRateService $exchangeRateService)
    {
        $toCurrency = $request->get('currency');
        $fromCurrency = ($toCurrency == ProductCurrencyType::EUR) ? ProductCurrencyType::USD : ProductCurrencyType::EUR;

        $exchangeRate = isset($toCurrency) ? $exchangeRateService->exchangeRate($fromCurrency, $toCurrency) : 1;

        $products = $productRepository->findFeatured($toCurrency, $exchangeRate);

        return $this->json([
            'products' => $products
        ], JsonResponse::HTTP_OK, [], [
            'groups' => ['product_read']
        ]);
    }
}