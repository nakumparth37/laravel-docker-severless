<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class S3Helper
{
    /**
     * Generate a Pre-Signed URL for a given S3 file path.
     *
     * @param string $fileUrl The full S3 file URL.
     * @param int $expiryMinutes The expiration time for the pre-signed URL in minutes.
     * @return string|null The pre-signed URL or null if the operation fails.
     */
    public static function generatePreSignedUrl(string $fileUrl, int $expiryMinutes = 30): ?string
    {
        try {
            // Generate the temporary pre-signed URL
            $expiration = now()->addMinutes($expiryMinutes);
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/' . env('AWS_BUCKET') . '/');
            return Storage::disk('s3')->temporaryUrl($filePath, $expiration);
        } catch (\Exception $e) {
            Log::error("Failed to generate pre-signed URL: " . $e->getMessage());
            return null;
        }
    }
}
