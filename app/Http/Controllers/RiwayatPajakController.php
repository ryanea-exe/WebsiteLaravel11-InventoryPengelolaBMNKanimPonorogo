<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPajak;
use App\Models\Kendaraan;
use Illuminate\Http\Request;

class RiwayatPajakController extends Controller
{
    public function index()
    {
        return view('pages.pemeliharaan.riwayat_pajak', [
            'riwayatPajak' => RiwayatPajak::with('kendaraan')
                ->latest()
                ->get(),
            'kendaraan' => Kendaraan::orderBy('nama_kendaraan')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id'  => 'required|exists:kendaraan,id',
            'tanggal_pajak' => 'required|date',
            'nama_pengurus' => 'nullable|string|max:100',
            'keterangan'    => 'nullable'
        ]);

        RiwayatPajak::create($request->all());

        return back()->with('success', 'Riwayat pajak berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = RiwayatPajak::findOrFail($id);

        $request->validate([
            'kendaraan_id'  => 'required|exists:kendaraan,id',
            'tanggal_pajak' => 'required|date',
            'nama_pengurus' => 'nullable|string|max:100',
            'keterangan'    => 'nullable'
        ]);

        $data->update($request->all());

        return back()->with('success', 'Riwayat pajak berhasil diperbarui.');
    }

    public function destroy($id)
    {
        RiwayatPajak::findOrFail($id)->delete();

        return back()->with('success', 'Riwayat pajak berhasil dihapus.');
    }
}
