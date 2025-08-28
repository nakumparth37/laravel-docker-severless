<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BaseFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $subcategory;
    protected $token;
    protected $userID;
    protected $thumbnail;
    protected $skipAuth = false;

    protected function setUp(): void
    {

        parent::setUp();

        // Log environment config diagnostics
        \Illuminate\Support\Facades\Log::info('⏳ BaseFeatureTest Bootstrapping', [
            'APP_ENV' => config('app.env'),
            'DB_CONNECTION' => config('database.default'),
            'DB_DATABASE' => config('database.connections.mysql.database'),
            'AUTH_GUARDS' => config('auth.guards'),
            'PASSPORT_CLIENT_ID' => config('passport.personal_access_client.id'),
        ]);

        $this->createOAuthClient();

        Artisan::call('db:seed', ['--class' => \Database\Seeders\RolesTableSeeder::class]);

        if (!$this->skipAuth) {
            $this->user = User::factory()->create([
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 2,
            ]);
            $this->userID = $this->user->id;

            $this->category = Category::factory()->create();
            $this->subcategory = SubCategory::factory()->create(['category_id' => $this->category->id]);

            $this->getLoginUserAuthToken();
        }
    }

    protected function sendApiRequest($endpoint, $method, $payload = [], $user = null)
    {
        if (!$this->token) {
            throw new \Exception('Token is missing!');
        }

        $headers = ['Authorization' => 'Bearer ' . $this->token];

        switch (strtoupper($method)) {
            case 'POST':
                return $this->postJson($endpoint, $payload, $headers);
            case 'PUT':
                return $this->putJson($endpoint, $payload, $headers);
            case 'DELETE':
                return $this->deleteJson($endpoint, $headers);
            case 'GET':
            default:
                return $this->getJson($endpoint, $headers);
        }
    }

    protected function createOAuthClient()
    {
        $this->artisan('migrate');

        $clientRepository = new ClientRepository();

        // Create personal access client
        $client = $clientRepository->createPersonalAccessClient(
            null,
            'Test Personal Access Client',
            'http://localhost'
        );

        config([
            'passport.personal_access_client.id' => $client->id,
            'passport.personal_access_client.secret' => $client->secret,
        ]);
    }

    protected function getLoginUserAuthToken()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $status = $response->status();
        $data = $response->json();

        Log::info('Login Status: ' . $status);
        Log::info('Login Response:', $data);

        if (isset($data['token']['token'])) {
            $this->token = $data['token']['token'];
        } elseif (isset($data['token'])) {
            $this->token = $data['token'];
        } else {
            throw new \Exception('Token not found in login response');
        }
    }

protected function getImageForTestUpload()
{
    Storage::fake('public');

    $imageDir = public_path('test/images');

    // Step 1: Create the directory if it doesn't exist
    if (!File::exists($imageDir)) {
        File::makeDirectory($imageDir, 0755, true);
    }

    // Step 2: Scan for existing images
    $files = File::allFiles($imageDir);

    // Step 3: If no images exist, create a fake one
    if (empty($files)) {
        $image = imagecreatetruecolor(200, 200); // width x height
        $bgColor = imagecolorallocate($image, 220, 220, 220);
        imagefill($image, 0, 0, $bgColor);

        $dummyPath = $imageDir . '/dummy.jpg';
        imagejpeg($image, $dummyPath);
        imagedestroy($image);

        $files = File::allFiles($imageDir); // refresh
    }

    // Step 4: Pick a random image from the directory
    $randomImage = $files[array_rand($files)];

    // Step 5: Return as UploadedFile
    return new UploadedFile(
        $randomImage->getPathname(),
        $randomImage->getFilename(),
        mime_content_type($randomImage->getPathname()),
        null,
        true
    );
}


/*     protected function getImageForTestUpload()
    {
        Storage::fake('public');
        $imagePath = public_path('test/images');
        $files = File::allFiles($imagePath);
        $randomImage = $files[array_rand($files)];

        return new UploadedFile(
            $randomImage->getPathname(),
            $randomImage->getFilename(),
            mime_content_type($randomImage->getPathname()),
            null,
            true
        );
    } */

    protected function getDynamicUpdateData($productData)
    {
        return [
            [
                'price' => $productData['price'],
                'title' => $productData['title'],
                'stock' => $productData['stock'],
            ],
            [
                'images' => $productData['images'],
                'discountPercentage' => $productData['discountPercentage'],
                'description' => $productData['description'],
            ],
            [
                'brand' => $productData['brand'],
                'category' => $productData['category'],
                'subCategory' => $productData['subCategory'],
            ],
        ];
    }



}
