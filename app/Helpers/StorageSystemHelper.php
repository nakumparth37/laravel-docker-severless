<?php

namespace App\Helpers;

use App\Models\Setting;

class StorageSystemHelper
{
    /**
     * Calculate the price after applying a discount percentage.
     *
     * @param float $price          Original price of the product
     * @param float $discountPercent Discount percentage to apply
     * @return float                Final price after discount
     */
    public static function checkTypeofStorageSystem() : string
    {
        return Setting::where('key', 'storage_type')->value('value') ?? 'local';

    }
}
