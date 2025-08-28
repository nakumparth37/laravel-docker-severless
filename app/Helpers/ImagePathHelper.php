<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImagePathHelper
{
    /**
     * Determine if the given image path is an S3 path or a public path.
     *
     * @param string $imagePath The path of the image from the database.
     * @return string 's3' if the path belongs to S3, 'public' if it is a local/public path.
     */
    public static function checkImagePath(string $imagePath = null): string
    {
        $bucketName = env('AWS_BUCKET');
        if (strpos($imagePath, $bucketName) !== false) {
            return 's3';
        }
        return 'public';
    }
}
