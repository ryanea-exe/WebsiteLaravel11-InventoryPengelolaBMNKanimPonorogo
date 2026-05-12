<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\RiwayatPajak;
use App\Models\RiwayatServis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InputPemeliharaanController extends Controller
{
    public function index_pajak()
    {
        $user = Auth::user();

        if ($user->role === 'Administrator') {
            $kendaraan = Kendaraan::orderBy('nama_kendaraan')->get();
        } else {
            $kendaraan = Kendaraan::where('seksi_id', $user->seksi_id)
                ->orderBy('nama_kendaraan')
                ->get();
        }

        return view('pages.pemeliharaan.input_pajak', [
            'kendaraan' => $kendaraan
        ]);
    }

    public function index_servis()
    {
        $user = Auth::user();

        if ($user->role === 'Administrator') {
            $kendaraan = Kendaraan::orderBy('nama_kendaraan')->get();
        } else {
            // hanya kendaraan sesuai seksi user
            $kendaraan = Kendaraan::where('seksi_id', $user->seksi_id)
                ->orderBy('nama_kendaraan')
                ->get();
        }

        return view('pages.pemeliharaan.input_servis', [
            'kendaraan' => $kendaraan
        ]);
    }
}
