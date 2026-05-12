<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catatan;
use Illuminate\Support\Facades\Auth;

class CatatanController extends Controller
{
    // =========================
    // STAFF - FORM INPUT
    // =========================
    public function index()
    {
        return view('pages.catatan.index');
    }

    // =========================
    // SIMPAN CATATAN
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'catatan' => 'required|string',
            'tanggal' => 'required|date'
        ]);

        Catatan::create([
            'catatan' => $request->catatan,
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil dikirim!');
    }

    // =========================
    // ADMIN - RIWAYAT
    // =========================
    public function riwayatAdmin()
    {
        $catatan = Catatan::with('user')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pages.catatan.riwayat_admin', compact('catatan'));
    }
}