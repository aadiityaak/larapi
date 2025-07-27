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
      'color' => $background['color'] ?? '#ffffff',
      'image' => $background['image'] ?? null,
      'style' => $background['style'] ?? 'center'
    ]);
  }

  /**
   * Store or update the background settings.
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'color' => 'nullable|string|max:50', // Lebih fleksibel untuk hex, rgba, dll
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Maksimal 1MB, tambah webp
      'style' => 'nullable|string|in:center,side',
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

    // Hapus gambar lama dari storage jika ada gambar baru
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      $oldImage = Setting::where('setting_key', 'background')->first();
      if ($oldImage) {
        $oldImageData = json_decode($oldImage->setting_value, true);
        if (isset($oldImageData['image'])) {
          // Extract path dari URL untuk menghapus file
          $oldImageUrl = $oldImageData['image'];
          $oldImagePath = str_replace(asset('storage/'), '', $oldImageUrl);
          if ($oldImagePath !== $oldImageUrl) { // Pastikan ini file dari storage
            Storage::disk('public')->delete($oldImagePath);
          }
        }
      }

      $imagePath = $request->file('image')->store('backgrounds', 'public');
      $background['image'] = asset('storage/' . $imagePath);
    }

    // Jika tidak ada gambar baru, gunakan gambar lama
    if (!isset($background['image'])) {
      $oldImage = Setting::where('setting_key', 'background')->first();
      if ($oldImage) {
        $oldImageData = json_decode($oldImage->setting_value, true);
        $background['image'] = $oldImageData['image'] ?? null;
      }
    }

    // Simpan ke database
    Setting::updateOrCreate(
      ['setting_key' => 'background'],
      ['setting_value' => json_encode($background)],
    );

    return response()->json([
      'success' => true,
      'message' => 'Pengaturan latar belakang berhasil disimpan.',
      'data' => [
        'color' => $background['color'] ?? '',
        'image' => $background['image'] ?? null,
        'style' => $background['style'] ?? 'center'
      ]
    ]);
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
