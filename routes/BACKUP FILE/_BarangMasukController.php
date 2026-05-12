<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        return view('pages.barang-masuk.index', [
            'barangMasuk' => BarangMasuk::with('barang')->latest()->get(),
            'barang' => Barang::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'    => 'required|exists:barangs,id',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'nullable|numeric|min:0',
            'tanggal'      => 'required|date',
            'keterangan'   => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $barang = Barang::lockForUpdate()->findOrFail($request->barang_id);

            $barang->increment('jumlah', $request->jumlah);

            $hargaSatuan = $request->harga_satuan ?? null;

            BarangMasuk::create([
                'barang_id'    => $barang->id,
                'jumlah'       => $request->jumlah,
                'harga_satuan' => $hargaSatuan,
                'harga_total'  => $hargaSatuan ? $request->jumlah * $hargaSatuan : null,
                'tanggal'      => \Carbon\Carbon::parse($request->tanggal)->setTimeFrom(\Carbon\Carbon::now()),
                'keterangan'   => $request->keterangan,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Barang masuk berhasil disimpan');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    /*
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $barangMasuk = BarangMasuk::with('barang')->findOrFail($id);

            // kurangi stok barang
            if ($barangMasuk->barang) {
                $barangMasuk->barang->decrement('jumlah', $barangMasuk->jumlah);
            }

            $barangMasuk->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data barang masuk berhasil dihapus');

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data barang masuk');
        }
    }
    */

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $barangMasuk = BarangMasuk::with('barang')->findOrFail($id);

            if ($barangMasuk->barang) {
                // cek agar stok tidak minus
                if ($barangMasuk->barang->jumlah < $barangMasuk->jumlah) {
                    return redirect()->back()
                        ->with('error', 'Stok barang tidak mencukupi untuk menghapus data ini');
                }

                $barangMasuk->barang->decrement('jumlah', $barangMasuk->jumlah);
            }

            $barangMasuk->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data barang masuk berhasil dihapus');

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data barang masuk');
        }
    }
}
