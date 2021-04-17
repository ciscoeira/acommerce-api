<?php

namespace App\DataFixtures;

use App\DBAL\Types\ProductCurrencyType;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Product::class, 100, function(Product $product, $count) use ($manager) {
            $product->setName($this->faker->word)
                ->setCurrency($this->faker->randomElement(ProductCurrencyType::getChoices()))
                ->setPrice($this->faker->randomFloat(2, 10, 1000))
                ->setFeatured($this->faker->boolean)
                ->setCategory($this->getReference(Category::class . '_' . rand(0, 19)));
        });

        $manager->flush();
    }
}
