<?php

namespace App\Services;

use App\Helpers\ImagePathHelper;
use App\Helpers\S3Helper;

class ImageService
{
    public function assignImageUrl($object, $imageField)
    {
        if (is_string($object)) {
            return $this->processImageString($object);
        }

        // Handle the case where the input is an object (e.g., Eloquent model or stdClass)
        if (is_object($object)) {
            return $this->processObject($object, $imageField);
        }

        if (is_array($object)) {
            return $this->processArray($object, $imageField);
        }

        return $object;
    }

    /**
     * Process image paths in a comma-separated string.
     */
    private function processImageString($imageString)
    {
        $images = explode(',', $imageString);
        $tempArray = [];

        foreach ($images as $image) {
            $tempArray[] = ImagePathHelper::checkImagePath($image) === 's3'
                ? S3Helper::generatePreSignedUrl($image)
                : $image;
            }

        return implode(',', $tempArray);
    }

    /**
     * Process an object, replacing the image field with a pre-signed URL if it's an S3 URL.
     */
    private function processObject($object, $imageField)
    {
        $imagePath = $object->$imageField;

        if (ImagePathHelper::checkImagePath($imagePath) === 's3') {
            $object->$imageField = S3Helper::generatePreSignedUrl($imagePath);
        }

        return $object;
    }

    /**
     * Process an array, replacing the image field with a pre-signed URL if it's an S3 URL.
     */
    private function processArray($array, $imageField)
    {
        foreach ($array as $key => $item) {
            $imagePath = $item[$imageField] ?? null;
            if ($imagePath && ImagePathHelper::checkImagePath($imagePath) === 's3') {
                $array[$key][$imageField] = S3Helper::generatePreSignedUrl($imagePath);
            }
        }

        return $array;
    }
}
