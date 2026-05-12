<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with([
            'user',
            'details' => function ($q) {
                $q->where('status', 'Disetujui')
                ->with('barang');
            }
        ])
        ->whereIn('status', ['Disetujui', 'Disetujui Sebagian'])
        ->whereHas('details', function ($q) {
            $q->where('status', 'Disetujui');
        });

        // Filter tanggal jika ada
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_proses', $request->tanggal);
        }

        $pengajuans = $query
            ->orderBy('tanggal_proses', 'desc')
            ->get();

        return view('pages.barang-keluar.index', compact('pengajuans'));
    }

    public function detail($id)
    {
        $pengajuan = Pengajuan::with([
            'user',
            'details.barang'
        ])->findOrFail($id);

        // Ambil hanya yang disetujui
        $details = $pengajuan->details
            ->where('status', 'Disetujui')
            ->values();

        return response()->json([
            'kode_pengajuan'  => $pengajuan->kode_pengajuan,
            'tanggal_proses' => $pengajuan->tanggal_proses,
            'user'           => $pengajuan->user,
            'keperluan'     => $pengajuan->keperluan,
            'details'        => $details,
        ]);
    }

    public function destroy($id)
    {
        Pengajuan::findOrFail($id)->delete();
        return redirect()->route('barang-keluar.index')
            ->with('success', 'Data barang keluar berhasil dihapus');
    }
}
