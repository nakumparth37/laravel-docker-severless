<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function updateStorageType(Request $request)
    {
        $request->validate([
            'storage_type' => 'required|in:local,s3'
        ]);

        Setting::updateOrCreate(
            ['key' => 'storage_type'],
            ['value' => $request->storage_type],
            ['storage_type' => $request->storage_type],
        );

        return back()->with('success', 'Storage type updated successfully.');
    }

    public function showStorageSettings()
    {
        $storageType = Setting::where('key', 'storage_type')->value('value');
        return view('admin.storage', compact('storageType'));
    }

}
