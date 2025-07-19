<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        Setting::updateOrCreate(
            ['key' => 'daily_iron_price'],
            ['value' => $request->input('daily_iron_price')]
        );

        return redirect()->route('admin.settings.index')->with('success', __('Settings updated successfully.'));
    }
}