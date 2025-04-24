<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingLoginController extends Controller
{
  /**
   * Get the background settings.
   */
  public function index()
  {
    $setting = Setting::where('setting_key', 'background')->first();
    $background = $setting ? json_decode($setting->setting_value, true) : [];
    return response()->json([
      'color' => $background['color'] ?? 'rgba(0, 0, 0, 0.1)',
      'image' => $background['image'] ?? asset('assets/bg.jpeg'),
      'style' => $background['style'] ?? null
    ]);
  }

  /**
   * Store or update the background settings.
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'color' => 'nullable|string|max:7', // Format hex warna (#FFFFFF)
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
      'style' => 'nullable|string',
    ]);

    $background = [];

    // Simpan warna jika ada
    if ($request->has('color')) {
      $background['color'] = $request->input('color');
    }

    // Simpan style jika ada
    if ($request->has('style')) {
      $background['style'] = $request->input('style');
    }

    //hapus gambar lama dari storage
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      $oldImage = Setting::where('setting_key', 'background')->first();
      if ($oldImage) {
        $oldImagePath = $oldImage->setting_value;
        if (isset($oldImagePath['image'])) {
          $oldImage = $oldImagePath['image'];
          Storage::disk('public')->delete($oldImage);
        }
      }

      $imagePath = $request->file('image')->store('backgrounds', 'public');
      $background['image'] = asset('storage/' . $imagePath);
    }

    // jika tidak ada gambar baru, gunakan gambar lama
    if (!isset($background['image'])) {
      $oldImage = Setting::where('setting_key', 'background')->first();
      $background['image'] = $oldImage ? json_decode($oldImage->setting_value, true)['image'] : null;
    }

    // Simpan ke database
    Setting::updateOrCreate(
      ['setting_key' => 'background'],
      ['setting_value' => json_encode($background)],
    );

    return response()->json(['message' => 'Pengaturan latar belakang berhasil disimpan.']);
  }

  /**
   * Remove the background settings.
   */
  public function destroy()
  {
    Setting::where('setting_key', 'background')->delete();
    return response()->json(['message' => 'Pengaturan latar belakang berhasil dihapus.']);
  }
}
