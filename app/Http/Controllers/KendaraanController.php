<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Seksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KendaraanController extends Controller
{
    public function index()
    {
        $query = Kendaraan::with([
            'seksi',
            'riwayatPajak' => function($q){
                $q->whereYear('tanggal_pajak', now()->year);
            },
            'riwayatServis' => function($q){
                $q->latest('tanggal_servis');
            }
        ]);

        // =========================
        // FILTER ROLE STAFF
        // =========================
        if (Auth::user()->role == 'Staff') {
            $query->where('seksi_id', Auth::user()->seksi_id);
        }

        $kendaraan = $query->orderBy('nama_kendaraan', 'asc')->get();

        $seksis = Seksi::orderBy('seksi')->get();

        return view('pages.pemeliharaan.kendaraan', compact('kendaraan', 'seksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_polisi'    => 'required|unique:kendaraan,nomor_polisi',
            'nama_kendaraan'  => 'required|string|max:100',
            'jenis_kendaraan' => 'required',
            'tahun'           => 'required|integer|min:0',
            'seksi_id'        => 'required|exists:seksi,id',
        ]);

        Kendaraan::create($request->all());

        return back()->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');

        $spreadsheet = IOFactory::load($file->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        // Mapping seksi
        $seksis = Seksi::all()
            ->mapWithKeys(function ($item) {
                return [strtolower(trim($item->seksi_singkat)) => $item->id];
            })
            ->toArray();

        $inserted = 0;
        $error = [];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue;

            // VALIDASI MINIMAL
            if (empty($row[0]) || empty($row[1])) continue;

            // Hindari duplikat
            if (Kendaraan::where('nomor_polisi', $row[0])->exists()) continue;

            // SEKSI (BOLEH NULL)
            $seksi_id = null;
            if (!empty($row[4])) {
                $seksiText = strtolower(trim($row[4]));
                if (!isset($seksis[$seksiText])) {
                    $error[] = "Baris ke-" . ($index+1) . " : Seksi tidak ditemukan ($seksiText)";
                    continue;
                }
                $seksi_id = $seksis[$seksiText];
            }

            // TANGGAL PAJAK (BOLEH NULL)
            $tanggalPajak = null;
            if (!empty($row[5])) {
                try {
                    $tanggalPajak = Carbon::parse($row[5])->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggalPajak = null;
                }
            }

            // RENTANG SERVIS (BOLEH NULL)
            $rentangServis = null;
            if (!empty($row[6])) {
                if (is_numeric($row[6])) {
                    $rentangServis = (int) $row[6];
                }
            }

            // KETERANGAN (BOLEH NULL)
            $keterangan = !empty($row[7]) ? $row[7] : null;

            // INSERT
            Kendaraan::create([
                'nomor_polisi'          => $row[0],
                'nama_kendaraan'        => $row[1],
                'jenis_kendaraan'       => $row[2] ?? null,
                'tahun'                 => $row[3] ?? null,
                'seksi_id'              => $seksi_id,
                'tanggal_pajak_berkala' => $tanggalPajak,
                'rentang_waktu_servis'  => $rentangServis,
                'keterangan'            => $keterangan,
            ]);

            $inserted++;
        }

        return back()->with([
            'success' => "$inserted data kendaraan berhasil diimport!",
            'error_import' => $error
        ]);
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $data = [
            'nomor_polisi' => $request->nomor_polisi,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'nama_kendaraan' => $request->nama_kendaraan,
            'tahun' => $request->tahun,
            'rentang_waktu_servis' => $request->rentang_waktu_servis,
            'keterangan' => $request->keterangan,
        ];

        // 🔒 HANYA ADMIN BOLEH UPDATE INI
        if (auth()->user()->role === 'Administrator') {
            $data['seksi_id'] = $request->seksi_id;
            $data['tanggal_pajak_berkala'] = $request->tanggal_pajak_berkala;
        }

        $kendaraan->update($data);

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    public function checkNopol(Request $request)
    {
        $nopol = $request->nomor_polisi;
        $ignoreId = $request->ignore_id;

        $exists = Kendaraan::where('nomor_polisi', $nopol)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    public function destroy($id)
    {
        Kendaraan::findOrFail($id)->delete();

        return back()->with('success', 'Data kendaraan berhasil dihapus.');
    }
}
