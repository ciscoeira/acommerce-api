<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findFeatured(?string $currency, float $exchangeRate)
    {
        $products = $this->findBy(['featured' => true]);

        if (!isset($currency)) {
            return $products;
        }

        // Convert prices to new currency if necessary
        return array_map(function ($product) use ($currency, $exchangeRate) {
            /** @var Product $product */
            return $product->convertPrice($currency, $exchangeRate);
        }, $products);
    }
}
