<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingFaviconController extends Controller
{
  /**
   * Get the favicon settings.
   */
  public function index()
  {
    $setting = Setting::where('setting_key', 'favicon')->first();
    $favicon = $setting ? json_decode($setting->setting_value, true) : [];

    return response()->json([
      'favicon' => $favicon['favicon'] ?? null,
    ]);
  }

  /**
   * Store or update the favicon settings.
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'favicon' => 'nullable|image|mimes:png,ico,jpg|max:1024', // Maksimal 1MB, hanya PNG atau ICO
    ]);

    $favicon = [];

    // Ambil data lama
    $setting = Setting::where('setting_key', 'favicon')->first();
    $oldFavicon = $setting ? json_decode($setting->setting_value, true) : [];

    // Hapus favicon lama jika ada favicon baru yang diunggah
    if ($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
      if (!empty($oldFavicon['favicon'])) {
        Storage::disk('public')->delete(str_replace(asset('storage/'), '', $oldFavicon['favicon']));
      }

      $faviconPath = $request->file('favicon')->store('favicons', 'public');
      $favicon['favicon'] = asset('storage/' . $faviconPath);
    } else {
      $favicon['favicon'] = $oldFavicon['favicon'] ?? null;
    }

    // Simpan ke database
    Setting::updateOrCreate(
      ['setting_key' => 'favicon'],
      ['setting_value' => json_encode($favicon)]
    );

    return response()->json(['message' => 'Favicon berhasil disimpan.']);
  }

  /**
   * Remove the favicon settings.
   */
  public function destroy()
  {
    $setting = Setting::where('setting_key', 'favicon')->first();
    if ($setting) {
      $favicon = json_decode($setting->setting_value, true);
      if (!empty($favicon['favicon'])) {
        Storage::disk('public')->delete(str_replace(asset('storage/'), '', $favicon['favicon']));
      }
      $setting->delete();
    }

    return response()->json(['message' => 'Favicon berhasil dihapus.']);
  }
}
