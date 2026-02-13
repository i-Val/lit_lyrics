<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            $key = $setting->key;

            if ($request->hasFile($key)) {
                // Handle File Upload
                $file = $request->file($key);
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/settings', $filename);
                
                $setting->value = $path;
                $setting->save();
            } elseif ($request->has($key)) {
                // Handle Text/Boolean
                $setting->value = $request->input($key);
                $setting->save();
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
