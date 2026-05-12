<?php

namespace App\Http\Controllers;

use App\Models\RiwayatServis;
use App\Models\Kendaraan;
use Illuminate\Http\Request;

class RiwayatServisController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'Administrator') {
            $riwayatServis = RiwayatServis::with('kendaraan')
                ->latest()
                ->get();
        } else {
            // 🔥 hanya kendaraan sesuai seksi user
            $riwayatServis = RiwayatServis::with('kendaraan')
                ->whereHas('kendaraan', function ($q) use ($user) {
                    $q->where('seksi_id', $user->seksi_id);
                })
                ->latest()
                ->get();
        }

        return view('pages.pemeliharaan.riwayat_servis', [
            'riwayatServis' => $riwayatServis,
            'kendaraan' => Kendaraan::orderBy('nama_kendaraan')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id'   => 'required|exists:kendaraan,id',
            'tanggal_servis' => 'required|date',
            'nama_pengurus'  => 'nullable|string|max:100',
            'keterangan'     => 'nullable'
        ]);

        RiwayatServis::create($request->all());

        return back()->with('success', 'Riwayat servis berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = RiwayatServis::findOrFail($id);

        $request->validate([
            'kendaraan_id'   => 'required|exists:kendaraan,id',
            'tanggal_servis' => 'required|date',
            'nama_pengurus'  => 'nullable|string|max:100',
            'keterangan'     => 'nullable'
        ]);

        $data->update($request->all());

        return back()->with('success', 'Riwayat servis berhasil diperbarui.');
    }

    public function destroy($id)
    {
        RiwayatServis::findOrFail($id)->delete();

        return back()->with('success', 'Riwayat servis berhasil dihapus.');
    }
}
