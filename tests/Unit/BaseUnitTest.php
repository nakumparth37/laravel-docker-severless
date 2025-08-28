<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseUnitTest extends TestCase
{
    use RefreshDatabase;
    protected $users = [];
    protected $categories = [];
    protected $subcategories = [];
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        // Log environment config diagnostics
        \Illuminate\Support\Facades\Log::info('⏳ BaseUnitTest Bootstrapping', [
            'APP_ENV' => config('app.env'),
            'DB_CONNECTION' => config('database.default'),
            'DB_DATABASE' => config('database.connections.mysql.database'),
            'AUTH_GUARDS' => config('auth.guards'),
            'PASSPORT_CLIENT_ID' => config('passport.personal_access_client.id'),
        ]);
        // Seed roles table
        $this->seed(\Database\Seeders\RolesTableSeeder::class);
        $this->users = User::factory(2)->create(['role_id' => 2]);

        // Create a category
        $this->categories = Category::factory(2)->create();

        // Create a subcategory
        $this->subcategories = [];
        foreach ($this->categories as $category) {
            $this->subcategories[] = Subcategory::factory(1)->create(['category_id' => $category->id])->first();
        }

        $seller = $this->users->first(); // Taking the first seller user
        $category = $this->categories->first(); // Taking the first category
        $subcategory = $this->subcategories[0]; // Taking the first subcategory
        // Create a product
        $this->product = Product::factory()->create([
            'sellerId' => $seller->id,
            'categoryId' => $category->id,
            'sub_categoryId' => $subcategory->id,
        ]);
    }
}
