<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with('barang')
            ->where('status', 'Disetujui');

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('barang', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%$search%")
                ->orWhere('kode_barang', 'like', "%$search%");
            });
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_proses', $request->tanggal);
        }

        $pengajuans = $query
            ->orderBy('tanggal_proses', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.barang-keluar.index', compact('pengajuans'));
    }

    public function destroy($id)
    {
        Pengajuan::findOrFail($id)->delete();
        return redirect()->route('barang-keluar.index')
            ->with('success', 'Data barang keluar berhasil dihapus');
    }
}
