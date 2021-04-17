<?php

namespace App\Service\Entity;

use App\Entity\Category;
use App\Entity\Product;

class ProductService extends BaseEntityService
{
    protected $entityClass = Product::class;

    public function setProperties(array $properties = []): parent
    {
        $properties['category'] = isset($properties['category'])
            ? $this->om->getRepository(Category::class)->find($properties['category']['id'])
            : null;

        return parent::setProperties($properties);
    }

    public function findFeatured(?string $currency, float $exchangeRate)
    {
        return $this->om->getRepository($this->entityClass)->findFeatured($currency, $exchangeRate);
    }
}