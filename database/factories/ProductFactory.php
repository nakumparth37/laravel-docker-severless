<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $category;
    protected $subcategory;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->category = Category::factory()->create();
        $this->subcategory = SubCategory::factory()->create(['category_id' => $this->category->id]);
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'discountPercentage' => $this->faker->randomFloat(2, 0, 50),
            'stock' => $this->faker->numberBetween(1, 100),
            'brand' => $this->faker->company(),
            'categoryId' =>  $this->category->id,
            'sub_categoryId' => $this->subcategory->id,
            'thumbnail' => $this->faker->imageUrl(),
            'images' => json_encode([$this->faker->imageUrl(), $this->faker->imageUrl()]),
            'sellerId' => \App\Models\User::whereHas('role', function ($query) {
                $query->where('role_id', '2');
            })->inRandomOrder()->first()->id,
        ];
    }
}
