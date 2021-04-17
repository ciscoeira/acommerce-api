<?php

namespace App\Service\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Service\ValidationService;
use Doctrine\Persistence\ObjectManager;

class ProductService extends BaseEntityService
{
    protected $entityClass = Product::class;
    protected $categoryRepository;

    /**
     * ProductService constructor.
     * @param ObjectManager $om
     * @param ValidationService $validator
     * @param CategoryRepository $categoryRepository
     * @throws \Exception
     */
    public function __construct(ObjectManager $om, ValidationService $validator, CategoryRepository $categoryRepository)
    {
        parent::__construct($om, $validator);
        $this->categoryRepository = $categoryRepository;
    }


    public function setProperties(array $properties = []): parent
    {
        $properties['category'] = isset($properties['category']) ? $this->categoryRepository->find($properties['category']['id']) : null;

        return parent::setProperties($properties);
    }
}