<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Unit\BaseUnitTest;


class ProductTest extends BaseUnitTest
{
    /** @test */
    public function it_creates_a_product_with_valid_Seller()
    {

        // Assertions
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'sellerId' => $this->product->sellerId,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_category()
    {
        $this->assertEquals($this->product->categoryId, $this->categories->first()->id);
    }

    /** @test */
    public function it_belongs_to_a_sub_category()
    {
        $this->assertEquals($this->product->sub_categoryId, $this->subcategories[0]->id);
    }

    /** @test */
    public function it_belongs_to_a_seller()
    {
        $this->assertEquals($this->product->sellerId, $this->users->first()->id);
    }

    public function test_it_creates_a_product_with_valid_data()
    {
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'title' => $this->product->title,
        ]);
    }

    /**
     * Test creating multiple products with dynamic sellers, categories, and subcategories.
     *
     * @test
     * @dataProvider productDataProvider
     */
    public function it_creates_multiple_products_with_different_sellers_categories_subcategories($userIndex, $categoryIndex, $subcategoryIndex)
    {
        // Get user, category, and subcategory for the test case
        $user = $this->users[$userIndex];
        $category = $this->categories[$categoryIndex];
        $subcategory = $this->subcategories[$subcategoryIndex];

        // Create a product with a specific seller, category, and subcategory
        $product = Product::factory()->create([
            'sellerId' => $user->id,
            'categoryId' => $category->id,
            'sub_categoryId' => $subcategory->id,
        ]);

        // Assert that the product belongs to the correct seller, category, and subcategory
        $this->assertEquals($user->id, $product->sellerId);
        $this->assertEquals($category->id, $product->categoryId);
        $this->assertEquals($subcategory->id, $product->sub_categoryId);

        // Assert that the product is in the database
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    /**
     * Data provider for testing products with different sellers, categories, and subcategories.
     *
     * @return array
     */
    public function productDataProvider()
    {
        return [
            [0, 0, 0], // Product 1: Seller 1, Category 1, Subcategory 1
            [0, 1, 1], // Product 2: Seller 1, Category 2, Subcategory 2
            [1, 0, 1], // Product 3: Seller 2, Category 1, Subcategory 2
            [1, 1, 0], // Product 4: Seller 2, Category 2, Subcategory 1
        ];
    }


}
