<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the settings.
     */
    public function index()
    {
        $settings = Setting::all(); // Retrieve all settings
        return response()->json($settings);
    }

    /**
     * Store or update settings in storage.
     */
    public function store(StoreSettingRequest $request)
    {
        $validatedData = $request->validated();
        $settingsToSave = [];

        // Handle file uploads
        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            $settingsToSave['favicon'] = $faviconPath;
        }

        if ($request->hasFile('pdf_sample')) {
            $pdfPath = $request->file('pdf_sample')->store('pdf_samples', 'public');
            $settingsToSave['pdf_sample'] = $pdfPath;
        }

        // Save or update each setting
        foreach ($validatedData as $key => $value) {
            $settingsToSave[$key] = $value; // Add other validated settings
            Setting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully.',
            'data' => $settingsToSave
        ], 201);
    }

    /**
     * Display the specified setting.
     */
    public function show($key)
    {
        $setting = Setting::where('setting_key', $key)->first();

        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'Setting not found.'], 404);
        }

        return response()->json($setting);
    }

    /**
     * Update the specified setting in storage.
     */
    public function update(UpdateSettingRequest $request, $key)
    {
        $setting = Setting::where('setting_key', $key)->first();

        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'Setting not found.'], 404);
        }

        $validatedData = $request->validated();
        $settingsToUpdate = [];

        // Handle file uploads
        if ($request->hasFile('favicon')) {
            if ($setting->setting_key === 'favicon') {
                Storage::disk('public')->delete($setting->setting_value);
            }
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            $settingsToUpdate['favicon'] = $faviconPath;
        }

        if ($request->hasFile('pdf_sample')) {
            if ($setting->setting_key === 'pdf_sample') {
                Storage::disk('public')->delete($setting->setting_value);
            }
            $pdfPath = $request->file('pdf_sample')->store('pdf_samples', 'public');
            $settingsToUpdate['pdf_sample'] = $pdfPath;
        }

        // Update the setting value
        foreach ($validatedData as $key => $value) {
            $settingsToUpdate[$key] = $value;
            $setting->update(['setting_value' => $value]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully.',
            'data' => $setting
        ], 200);
    }

    /**
     * Remove the specified setting from storage.
     */
    public function destroy($key)
    {
        $setting = Setting::where('setting_key', $key)->first();

        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'Setting not found.'], 404);
        }

        // Delete associated file if exists
        if ($setting->setting_value) {
            Storage::disk('public')->delete($setting->setting_value);
        }

        // Delete the setting record
        $setting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully.'
        ], 200);
    }
}
