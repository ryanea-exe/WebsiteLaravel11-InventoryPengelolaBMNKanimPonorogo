<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->orderBy('kode_barang', 'asc')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('pages.barang.index', compact('barang', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'jumlah'      => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
        ]);

        Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'jumlah'      => $request->jumlah,
            'satuan'      => $request->satuan,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang persediaan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $id,
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'jumlah'      => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
        ]);

        Barang::findOrFail($id)->update([
            'kode_barang' => $request->kode_barang, // ← tambahkan ini
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'jumlah'      => $request->jumlah,
            'satuan'      => $request->satuan,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang persediaan berhasil diperbarui');
    }

    public function checkKode(Request $request)
    {
        $kode = $request->kode_barang;
        $ignoreId = $request->ignore_id;

        $exists = Barang::where('kode_barang', $kode)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');

        $spreadsheet = IOFactory::load($file->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        // Mapping kategori
        $kategoris = Kategori::all()
            ->mapWithKeys(function ($item) {
                return [strtolower(trim($item->nama)) => $item->id];
            })
            ->toArray();

        $inserted = 0;
        $error = [];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue;

            // VALIDASI MINIMAL
            if (empty($row[0]) || empty($row[1])) continue;

            // Hindari duplikat
            if (Barang::where('kode_barang', $row[0])->exists()) continue;

            // INSERT
            Kendaraan::create([
                'kode_barang' => $row[0],
                'nama_barang' => $row[1],
                'kategori_id' => $kategori_id,
                'jumlah'      => $jumlah,
                'satuan'      => $satuan,
            ]);

            $inserted++;
        }

        return back()->with([
            'success' => "$inserted data barang persediaan berhasil diimport!",
            'error_import' => $error
        ]);
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // ❌ Cegah hapus jika sudah pernah transaksi
        if ($barang->barangMasuk()->exists() || $barang->barangKeluar()->exists()) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang persediaan tidak dapat dihapus karena sudah memiliki riwayat transaksi');
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Data barang persediaaan berhasil dihapus');
    }
}
