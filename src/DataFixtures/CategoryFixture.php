<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Category::class, 20, function(Category $category, $count) use ($manager) {
            $category->setName($this->faker->word)
                ->setDescription($this->faker->sentence);
        });

        $manager->flush();
    }
}
