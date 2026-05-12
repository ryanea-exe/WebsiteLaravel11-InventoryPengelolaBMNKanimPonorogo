<?php

namespace App\Http\Controllers;

use App\Models\BarangBMN;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangBMNController extends Controller
{
    public function index()
    {
        $barang = BarangBMN::with('kategori')
            ->orderBy('kode_barang', 'asc')
            ->get();

        $kategoris = Kategori::orderBy('nama')->get();

        return view('pages.barang-bmn.index', compact('barang', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang_bmn,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'merk_type'   => 'required|string|max:100',
            'jumlah'      => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
        ]);

        BarangBMN::create($request->all());

        return redirect()->route('barang-bmn.index')
            ->with('success', 'Barang BMN berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang_bmn,kode_barang,' . $id,
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'merk_type'   => 'required|string|max:100',
            'jumlah'      => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
        ]);

        BarangBMN::findOrFail($id)->update($request->all());

        return redirect()->route('barang-bmn.index')
            ->with('success', 'Data Barang BMN berhasil diperbarui');
    }

    public function checkKode(Request $request)
    {
        $kode = $request->kode_barang;
        $ignoreId = $request->ignore_id;

        $exists = BarangBMN::where('kode_barang', $kode)
            ->when($ignoreId, function ($q) use ($ignoreId) {
                $q->where('id', '!=', $ignoreId);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function destroy($id)
    {
        BarangBMN::findOrFail($id)->delete();

        return redirect()->route('barang-bmn.index')
            ->with('success', 'Data Barang BMN berhasil dihapus');
    }
}
