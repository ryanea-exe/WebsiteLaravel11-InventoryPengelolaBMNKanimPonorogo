<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanController extends Controller
{
    /**
     * Menampilkan halaman form pengajuan barang
     */
    public function index()
    {
        $barangs = Barang::select('id','kode_barang','nama_barang','jumlah')
            ->orderBy('nama_barang')
            ->get();

        return view('pages.pengajuan.index', compact('barangs'));
    }

    /**
     * Menyimpan data pengajuan barang
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah'    => 'required|integer|min:1',
            'keperluan' => 'required|string'
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->jumlah == 0) {
            return back()->withErrors([
                'barang_id' => 'Stok barang habis, tidak dapat diajukan.'
            ]);
        }

        Pengajuan::create([
            'user_id'           => auth()->id(),
            'barang_id'         => $request->barang_id,
            'jumlah'            => $request->jumlah,
            'keperluan'         => $request->keperluan,
            'tanggal_pengajuan' => now(),
            'status'            => 'Diajukan'
        ]);

        return redirect()
            ->route('pengajuan.index')
            ->with('success', 'Pengajuan permintaan barang berhasil diajukan.');
    }

    // RIWAYAT
    public function riwayat()
    {
        $user = auth()->user();

        if ($user->role === 'Administrator') {
            return redirect()->route('pengajuan.riwayat_admin');
        }

        if ($user->role === 'Staff') {
            return redirect()->route('pengajuan.riwayat_user');
        }

        abort(403, 'Role tidak dikenali');
    }

    // RIWAYAT ADMIN
    public function riwayat_admin(Request $request)
    {
        $query = Pengajuan::with(['barang', 'user']);

        if (auth()->user()->role === 'Staff') {
            $query->where('user_id', auth()->id());
        }

        // 🏷 Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        //  Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pengajuan', $request->tanggal);
        }

        $pengajuans = $query
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.pengajuan.riwayat_admin', compact('pengajuans'));
    }

    // RIWAYAT USER (HANYA DATA MILIK USER LOGIN)
    public function riwayat_user(Request $request)
    {
        // 🔴 TANDAI SEMUA NOTIFIKASI SEBAGAI SUDAH DIBACA
        Pengajuan::where('user_id', auth()->id())
            ->whereIn('status', ['Disetujui', 'Ditolak'])
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        $query = Pengajuan::with(['barang', 'user'])
            ->where('user_id', auth()->id());

        // 🏷 Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 📅 Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pengajuan', $request->tanggal);
        }

        $pengajuans = $query
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.pengajuan.riwayat_user', compact('pengajuans'));
    }

    // UPDATE STATUS (ADMIN)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diajukan,Disetujui,Ditolak'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $pengajuan = Pengajuan::with('barang')
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($pengajuan->status === 'Disetujui') {
                    throw new \Exception('Status sudah disetujui.');
                }

                if ($request->status === 'Disetujui') {
                    $barang = $pengajuan->barang;

                    if ($barang->jumlah < $pengajuan->jumlah) {
                        throw new \Exception('Stok barang tidak mencukupi.');
                    }

                    $barang->update([
                        'jumlah' => $barang->jumlah - $pengajuan->jumlah
                    ]);
                }

                $pengajuan->update([
                    'status' => $request->status,
                    'tanggal_proses' => in_array($request->status, ['Disetujui', 'Ditolak'])
                        ? now()
                        : null
                ]);
            });

            return redirect()
                ->route('pengajuan.riwayat_admin')
                ->with('success', 'Status berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Pengajuan::findOrFail($id)->delete();
        return redirect()->route('pengajuan.riwayat_admin')
            ->with('success', 'Permintaan berhasil dihapus');
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('barang')->findOrFail($id);
        return view('pages.pengajuan.show', compact('pengajuan'));
    }

    public function read($id)
    {
        $pengajuan = Pengajuan::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pengajuan->update([
            'read_at' => now()
        ]);

        return redirect()->route('pengajuan.riwayat_user');
    }

    // modal show data
    public function detail($id)
    {
        $pengajuan = Pengajuan::with(['barang', 'user'])->findOrFail($id);

        // 🔐 KEAMANAN:
        // Jika staff, hanya boleh lihat pengajuan milik sendiri
        if (auth()->user()->role === 'Staff' &&
            $pengajuan->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->json($pengajuan);
    }

    // cetak nodin
    public function nodin($id)
    {
        $pengajuan = Pengajuan::with(['barang','user'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.pengajuan.pdf.nodin', compact('pengajuan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('nota-dinas.pdf'); // preview sebelum print
    }

    // cetak bast
    public function bast($id)
    {
        $pengajuan = Pengajuan::with(['barang','user'])->findOrFail($id);

        if ($pengajuan->status !== 'Disetujui') {
            abort(403, 'BAST hanya untuk pengajuan yang disetujui');
        }

        $pdf = Pdf::loadView('pages.pengajuan.pdf.bast', compact('pengajuan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('berita-acara-serah-terima.pdf');
    }
}
