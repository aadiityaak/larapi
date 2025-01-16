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
    $setting_key = $request->get('setting_key');
    $settings = Setting::all();

    $datas = [];
    foreach ($settings as $setting) {
      $datas[$setting->setting_key] = $setting->setting_value;
    }

    if ($setting_key && isset($datas[$setting_key])) {
      return response()->json([$setting_key => $datas[$setting_key]]);
    }

    return response()->json($datas);
  }

  /**
   * Store or update settings in storage.
   */
  public function store(Request $request)
  {
    // Define validation rules
    $validatedData = $request->validate([
      'app_name' => 'nullable|string|max:255',
      'app_description' => 'nullable|string|max:500',
      'alamat' => 'nullable|string|max:255',
      'banks' => 'nullable|string|max:2000',
      'pekerjaan' => 'nullable|string|max:10000',
      'pdf_sample' => 'nullable',
      'email' => 'nullable|email|max:255',
    ]);

    if (is_string($request->pdf_sample)) {
      unset($validatedData['pdf_sample']);
    }
    $settingsToSave = [];

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
  public function update(Request $request, $key)
  {
    $setting = Setting::where('setting_key', $key)->first();

    if (!$setting) {
      return response()->json(['success' => false, 'message' => 'Setting not found.'], 404);
    }

    // Define validation rules
    $validatedData = $request->validate([
      'app_name' => 'nullable|string|max:255',
      'app_description' => 'nullable|string|max:500',
      'alamat' => 'nullable|string|max:255',
      'banks' => 'nullable|string|max:2000',
      'pekerjaan' => 'nullable|string|max:2000',
      'pdf_sample' => 'nullable|file|mimes:pdf|max:2048',
      'email' => 'nullable|email|max:255',
    ]);

    if (is_string($request->pdf_sample)) {
      unset($validatedData['pdf_sample']);
    }

    $settingsToUpdate = [];

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
