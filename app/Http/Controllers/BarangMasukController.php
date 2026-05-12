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
            'barangMasuk' => BarangMasuk::with('details.barang')->latest()->get(),
            'barang' => Barang::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'        => 'required|date',
            'barang_id.*'    => 'required|exists:barangs,id',
            'jumlah.*'       => 'required|integer|min:1',
            'harga_satuan.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // 1. simpan header
            $barangMasuk = BarangMasuk::create([
                'tanggal'    => \Carbon\Carbon::parse($request->tanggal)->setTimeFrom(now()),
                'keterangan' => $request->keterangan,
            ]);

            // 2. loop detail
            foreach ($request->barang_id as $index => $barangId) {
                $jumlah = $request->jumlah[$index];
                $hargaSatuan = $request->harga_satuan[$index] ?? 0;

                $barang = Barang::lockForUpdate()->findOrFail($barangId);

                // update stok
                $barang->increment('jumlah', $jumlah);

                // simpan detail
                $barangMasuk->details()->create([
                    'barang_id'    => $barangId,
                    'jumlah'       => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'harga_total'  => $hargaSatuan * $jumlah,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Barang masuk berhasil disimpan');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = BarangMasuk::with('details.barang')->findOrFail($id);

        return response()->json([
            'id'         => $data->id,
            'tanggal'    => $data->tanggal->format('Y-m-d'),
            'keterangan' => $data->keterangan,
            'details'    => $data->details->map(function ($item) {
                return [
                    'barang_id'    => $item->barang_id,
                    'nama_barang'  => $item->barang->nama_barang,
                    'jumlah'       => $item->jumlah,
                    'harga_satuan' => $item->harga_satuan,
                ];
            })
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'        => 'required|date',
            'barang_id.*'    => 'required|exists:barangs,id',
            'jumlah.*'       => 'required|integer|min:1',
            'harga_satuan.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $barangMasuk = BarangMasuk::with('details')->findOrFail($id);

            // 🔥 1. KEMBALIKAN STOK LAMA
            foreach ($barangMasuk->details as $detail) {
                $barang = Barang::lockForUpdate()->find($detail->barang_id);
                $barang->decrement('jumlah', $detail->jumlah);
            }

            // 🔥 2. HAPUS DETAIL LAMA
            $barangMasuk->details()->delete();

            // 🔥 3. UPDATE HEADER
            $barangMasuk->update([
                'tanggal' => \Carbon\Carbon::parse($request->tanggal)->setTimeFrom(now()),
                'keterangan' => $request->keterangan,
            ]);

            // 🔥 4. SIMPAN DETAIL BARU + TAMBAH STOK
            foreach ($request->barang_id as $index => $barangId) {
                $jumlah = $request->jumlah[$index];
                $hargaSatuan = $request->harga_satuan[$index] ?? 0;

                $barang = Barang::lockForUpdate()->findOrFail($barangId);

                // tambah stok baru
                $barang->increment('jumlah', $jumlah);

                $barangMasuk->details()->create([
                    'barang_id'    => $barangId,
                    'jumlah'       => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'harga_total'  => $hargaSatuan * $jumlah,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Barang masuk berhasil diupdate');

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

    public function detail($id)
    {
        $data = \App\Models\BarangMasuk::with('details.barang')->findOrFail($id);

        return response()->json([
            'kode_transaksi' => $data->kode_transaksi ?? ('TRM-' . $data->id),
            'tanggal'        => \Carbon\Carbon::parse($data->tanggal)->format('d M Y, H:i'),
            'keterangan'     => $data->keterangan,
            'details'        => $data->details->map(function ($item) {
                return [
                    'jumlah'       => $item->jumlah,
                    'harga_satuan' => $item->harga_satuan,
                    'harga_total'  => $item->harga_total,
                    'barang' => [
                        'kode_barang' => $item->barang->kode_barang,
                        'nama_barang' => $item->barang->nama_barang,
                        'satuan'      => $item->barang->satuan,
                    ]
                ];
            })
        ]);
    }

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
