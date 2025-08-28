<?php

namespace Tests\Feature;


use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPUnit\Framework\returnSelf;

class ProductTest extends BaseFeatureTest
{

    public $productId;

    /** @test */
    public function a_user_can_create_a_product()
    {

        $this->category = Category::factory()->create([
            'category_Name' => 'Electronics',
        ]);

        $this->subcategory = SubCategory::factory()->create([
            'Sub_category_Name' => 'Mobile',
            'category_id' => $this->category->id,
        ]);

        // Create product data
        $productData = [
            'title' => 'iPhone 14',
            'description' => 'The latest iPhone model.',
            'price' => 999.99,
            'discountPercentage' => 10,
            'stock' => 50,
            'brand' => 'Apple',
            'category' => 'Electronics',
            'subCategory' => 'Mobile',
            'thumbnail' => $this->getImageForTestUpload(),
            'images' => [
                $this->getImageForTestUpload(),
            ],
            'seller' => 'user@example.com',
        ];
        // Call the generic request method
        $response = $this->sendApiRequest('/api/v1/admin/addNewProduct', 'POST', $productData, $this->user);


        //Debug log full response
        $responseData = $response->json();
        Log::info('Product Create Response:', $responseData);

        //Status check
        $response->assertStatus(200);

        //Safe access
        $product = $responseData['Product'] ?? $responseData['product'] ?? null;

        $this->assertNotNull($product, 'Product creation failed: No product returned.');
        $this->assertArrayHasKey('id', $product, 'Product creation failed: No ID in product.');

        $this->productId = $product['id'];
        //Assert the product was created in the database
        // Database check
        $this->assertDatabaseHas('products', [
            'title' => 'iPhone 14',
            'description' => 'The latest iPhone model.',
            'price' => 999.99,
            'stock' => 50,
        ]);


        //Assert the response is successful
        return $this->productId;
    }

    /** @test */
    public function a_user_can_update_a_product()
    {
        $this->category = Category::factory()->create([
            'category_Name' => 'Electronics',
        ]);

        $this->subcategory = SubCategory::factory()->create([
            'Sub_category_Name' => 'Mobile',
            'category_id' => $this->category->id,
        ]);

        $id = $this->a_user_can_create_a_product();
        // Create product data
        $productData = [
            'title' => 'iPhone 14',
            'description' => 'The latest iPhone model.',
            'price' => rand(100,500),
            'discountPercentage' => rand(10.5, 25.5),
            'stock' => rand(50,100),
            'brand' => 'Apple',
            'category' => 'Electronics',
            'subCategory' => 'Mobile',
            'thumbnail' => $this->getImageForTestUpload(),
            'images' => [
                $this->getImageForTestUpload(),
                $this->getImageForTestUpload(),
            ],
            'seller' => 'user@example.com',
        ];
        // Call the generic request method
        $dynamicProductUpdateArrays = $this->getDynamicUpdateData($productData);
        $radomSubset = $dynamicProductUpdateArrays[array_rand($dynamicProductUpdateArrays)];
        $response = $this->sendApiRequest("/api/v1/admin/updateProduct/$id", 'POST', $radomSubset, $this->user);

        //Assert the response is successful
        $response->assertStatus(200);
    }

    public function test_can_delete_a_product()
    {
        $id = $this->a_user_can_create_a_product();
        $response = $this->sendApiRequest("/api/v1/admin/deleteProduct/{$id}", 'DELETE', $this->user);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $id]);
    }


}
