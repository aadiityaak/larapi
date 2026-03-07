<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the settings.
     */
    public function index(Request $request)
    {
        $settings = Setting::all()->pluck('setting_value', 'setting_key')->toArray();

        if ($settingKey = $request->get('setting_key')) {
            return response()->json([$settingKey => $settings[$settingKey] ?? null]);
        }

        return response()->json($settings);
    }

    /**
     * Store or update settings in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules
        $validatedData = $request->validate($this->validationRules());

        // Handle file upload if exists
        if ($request->hasFile('pdf_sample')) {
            $validatedData['pdf_sample'] = $request->file('pdf_sample')->store('pdf_samples', 'public');
        }

        // Save or update settings
        $this->saveSettings($validatedData);

        return $this->responseSuccess('Settings saved successfully.', $validatedData);
    }

    /**
     * Display the specified setting.
     */
    public function show($key)
    {
        $setting = Setting::where('setting_key', $key)->firstOrFail();

        return response()->json($setting);
    }

    /**
     * Update the specified setting in storage.
     */
    public function update(Request $request, $key)
    {
        $setting = Setting::where('setting_key', $key)->firstOrFail();

        // Define validation rules
        $validatedData = $request->validate($this->validationRules());

        // Handle file upload and delete previous file if necessary
        if ($request->hasFile('pdf_sample')) {
            Storage::disk('public')->delete($setting->setting_value);
            $validatedData['pdf_sample'] = $request->file('pdf_sample')->store('pdf_samples', 'public');
        }

        // Update the setting
        $setting->update($validatedData);

        return $this->responseSuccess('Setting updated successfully.', $setting);
    }

    /**
     * Remove the specified setting from storage.
     */
    public function destroy($key)
    {
        $setting = Setting::where('setting_key', $key)->firstOrFail();

        // Delete associated file if exists
        if ($setting->setting_value) {
            Storage::disk('public')->delete($setting->setting_value);
        }

        // Delete the setting record
        $setting->delete();

        return $this->responseSuccess('Setting deleted successfully.');
    }

    /**
     * Get background settings specifically
     */
    public function getBackground()
    {
        $backgroundSettings = Setting::whereIn('setting_key', ['background_color', 'background_image', 'login_style'])
            ->pluck('setting_value', 'setting_key')
            ->toArray();

        return response()->json([
            'color' => $backgroundSettings['background_color'] ?? '',
            'image' => $backgroundSettings['background_image'] ? asset('storage/' . $backgroundSettings['background_image']) : null,
            'style' => $backgroundSettings['login_style'] ?? 'center'
        ]);
    }

    /**
     * Store background settings specifically
     */
    public function storeBackground(Request $request)
    {
        $validatedData = $request->validate([
            'color' => 'nullable|string|max:50',
            'style' => 'nullable|string|in:center,side',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 1MB max
        ]);

        $settings = [];

        // Handle color
        if (isset($validatedData['color'])) {
            $settings['background_color'] = $validatedData['color'];
        }

        // Handle style
        if (isset($validatedData['style'])) {
            $settings['login_style'] = $validatedData['style'];
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldImageSetting = Setting::where('setting_key', 'background_image')->first();
            if ($oldImageSetting && $oldImageSetting->setting_value) {
                Storage::disk('public')->delete($oldImageSetting->setting_value);
            }

            $imagePath = $request->file('image')->store('backgrounds', 'public');
            $settings['background_image'] = $imagePath;
        }

        // Save settings
        $this->saveSettings($settings);

        return $this->responseSuccess('Background settings saved successfully.', [
            'color' => $settings['background_color'] ?? '',
            'image' => isset($settings['background_image']) ? asset('storage/' . $settings['background_image']) : null,
            'style' => $settings['login_style'] ?? 'center'
        ]);
    }

    /**
     * Validation rules for storing/updating settings.
     */
    protected function validationRules()
    {
        return [
            'app_name' => 'nullable|string|max:255',
            'app_code' => 'nullable|string|max:255',
            'app_description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'banks' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'pdf_sample' => 'nullable',
            'email' => 'nullable|email|max:255',
            'new_order' => 'nullable|string',
            'project_assignment' => 'nullable|string',
            'followup_project' => 'nullable|string',

            // Background/Login settings
            'color' => 'nullable|string|max:50',
            'style' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 1MB max
        ];
    }

    /**
     * Save settings to the database.
     */
    protected function saveSettings(array $settings)
    {
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }
    }

    /**
     * Create a success response.
     */
    protected function responseSuccess(string $message, $data = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }
}
