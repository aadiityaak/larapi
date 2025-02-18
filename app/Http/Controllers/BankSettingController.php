<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class BankSettingController extends Controller
{
    /**
     * Get the list of banks from settings.
     */
    public function index()
    {
        $setting = Setting::where('setting_key', 'banks')->first();
        $banks = $setting ? json_decode($setting->setting_value, true) : [];

        return response()->json($banks);
    }

    /**
     * Store or update the bank settings.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'banks' => 'required|array',
            'banks.*.name' => 'required|string|max:255',
        ]);

        $banksJson = json_encode($validatedData['banks']);

        Setting::updateOrCreate(
            ['setting_key' => 'banks'],
            ['setting_value' => $banksJson]
        );

        return response()->json(['message' => 'Bank settings saved successfully.']);
    }

    /**
     * Remove the bank settings.
     */
    public function destroy()
    {
        Setting::where('setting_key', 'banks')->delete();

        return response()->json(['message' => 'Bank settings deleted successfully.']);
    }
}
