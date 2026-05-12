<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Setting2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();
        $setting2 = Setting2::first() ?? new Setting2();

        return view('pages.setting.index', compact('setting','setting2'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo'                => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'login_bg'            => 'nullable|image|mimes:png,jpg,jpeg|max:10240',
            'nama_aplikasi'       => 'required|string|max:50',
            'nama_aplikasi2'      => 'required|string|max:50',
            'subnama_aplikasi'    => 'required|string|max:255',
            'login_opening_text'  => 'required|string',
            'sidebar_color'       => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'sidebar_text_color'  => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'sidebar_hover_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'format_cetak'        => 'required',
        ]);

        $setting = Setting::first();

        if (!$setting) {
            $setting = new Setting();
        }

        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::exists('public/logo/'.$setting->logo)) {
                Storage::delete('public/logo/'.$setting->logo);
            }

            $file = $request->file('logo');
            $filename = 'logo_'.time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/logo', $filename);

            $setting->logo = $filename;
        }

        // Upload background login
        if ($request->hasFile('login_bg')) {
            if ($setting->login_bg && Storage::exists('public/login-bg/'.$setting->login_bg)) {
                Storage::delete('public/login-bg/'.$setting->login_bg);
            }

            $file = $request->file('login_bg');
            $filename = 'login_bg_'.time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/login-bg', $filename);

            $setting->login_bg = $filename;
        }

        $setting->nama_aplikasi = $request->nama_aplikasi;
        $setting->nama_aplikasi2 = $request->nama_aplikasi2;
        $setting->subnama_aplikasi = $request->subnama_aplikasi;
        $setting->login_opening_text = $request->login_opening_text;
        $setting->sidebar_color = $request->sidebar_color;
        $setting->sidebar_text_color = $request->sidebar_text_color;
        $setting->sidebar_hover_color = $request->sidebar_hover_color;
        $setting->format_cetak = $request->format_cetak;

        $setting->save();

        return redirect()
            ->route('setting.index', ['tab' => 'ui'])
            ->with('success_ui', 'Pengaturan UI berhasil diperbarui');
    }
}
