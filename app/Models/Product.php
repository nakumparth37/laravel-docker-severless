<?php

namespace App\Models;

use App\Helpers\StorageSystemHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'price',
        'discountPercentage',
        'stock',
        'brand',
        'categoryId',
        'sub_categoryId',
        'thumbnail',
        'images',
        'sellerId',
    ];

    public function saveProductThumbnail($thumbnail)
    {
        $storageType = StorageSystemHelper::checkTypeofStorageSystem();
        $thumbnailName = $thumbnail->getClientOriginalName();

        if ($storageType === 'local') {
            $thumbnailPath = $thumbnail->storeAs("Product/Product_$this->id/thumbnail", $thumbnailName, 'public');
            return url("uploads/$thumbnailPath");

        } elseif ($storageType === 's3') {
            //Upload file and Store files into AWS S3 bucket
            $folder = "Product/Product_$this->id/thumbnail";

            $path = $thumbnail->storeAs($folder, $thumbnailName, 's3');
            return Storage::disk('s3')->url($path);
        } else {
            throw new \Exception('Invalid storage type');
        }
    }

    public function saveProductImages($uploadedImages)
    {
        $storageType = StorageSystemHelper::checkTypeofStorageSystem();
        //Upload file and store files into the sever itself

        if ($storageType === 'local') {
            $images = [];
            foreach ($uploadedImages as $image) {
                $imageName = $image->getClientOriginalName();
                $imagePath = $image->storeAs("Product/Product_$this->id", $imageName, 'public');
                $images[] = url("uploads/$imagePath");
            }
            return $images;
        } elseif($storageType === 's3'){
            //Upload file and Store files into AWS S3 bucket
            $images = [];
            foreach ($uploadedImages as $image) {
                $imageName = $image->getClientOriginalName();
                $productDirectory = "Product/Product_$this->id";
                $path = $image->storeAs($productDirectory, $imageName, 's3');
                $images[] = Storage::disk('s3')->url($path);;
            }
            return $images;
        } else {
            throw new \Exception('Invalid storage type');
        }
    }

    public function deleteProductThumbnail()
    {
        $storageType = StorageSystemHelper::checkTypeofStorageSystem();
        if ($storageType === 'local') {
            //Delete files form the sever
            $baseFileName = basename($this->thumbnail);
            Storage::disk('public')->delete("Product/Product_$this->id/thumbnail/{$baseFileName}");
            File::deleteDirectory("uploads/Product/Product_$this->id/thumbnail");
        } elseif ($storageType === 's3') {
            //Delete file from the AWS S3 bucket
            if (!$this->thumbnail) {
                return; // No image to delete
            }
            $filePath = parse_url($this->thumbnail, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');
            $filePath = ltrim($filePath, '/' . env('AWS_BUCKET') . '/');
            Storage::disk('s3')->delete($filePath);
        } else {
            throw new \Exception('Invalid storage type');
        }
    }

    public function deleteProductImages()
    {
        $storageType = StorageSystemHelper::checkTypeofStorageSystem();
        //Delete files form the sever
        if ($storageType === 'local') {
            $allImages = explode(',', $this->images);
            foreach ($allImages as $key => $image) {
                $imageName = basename($image);
                Storage::disk('public')->delete("Product/Product_$this->id/{$imageName}");
            }
            if (!File::isDirectory("uploads/Product/Product_$this->id/thumbnail")) {
                File::deleteDirectory("uploads/Product/Product_$this->id");
            }
        } elseif ($storageType === 's3') {
            //Delete file from the AWS S3 bucket
            if (!$this->images) {
                return; // No image to delete
            }
            $allImages = explode(',', $this->images);
            foreach ($allImages as $key => $image) {
                $filePath = parse_url($image, PHP_URL_PATH);
                $filePath = ltrim($filePath, '/');
                $filePath = ltrim($filePath, '/' . env('AWS_BUCKET') . '/');
                Storage::disk('s3')->delete($filePath);
            }
        } else {
            throw new \Exception('Invalid storage type');
        }
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            $this->save();
            return true;
        }
        return false; // Not enough stock
    }

    //Scop activity Local
    public function scopeStock($query,$count = 5)
    {
        return $query->where('stock','>=',$count);
    }

}
