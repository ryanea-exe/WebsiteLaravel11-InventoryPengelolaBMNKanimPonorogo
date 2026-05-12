<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // tampilkan form login
    public function index()
    {
        $namaAplikasi = Setting::first()->nama_aplikasi;

        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login', compact('namaAplikasi'));
    }

    // proses login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        // hanya user aktif yang bisa login
        $credentials['status'] = 'Aktif';

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            Auth::user()->update([
                'last_login_at' => now()
            ]);

            return redirect()->route('dashboard.index')->with('success', 'Login berhasil.');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
