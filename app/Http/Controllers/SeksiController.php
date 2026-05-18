<?php

namespace App\Http\Controllers;

use App\Models\Seksi;
use Illuminate\Http\Request;

class SeksiController extends Controller
{
    public function index()
    {
        $seksis = Seksi::orderBy('seksi')->get();
        return view('pages.user.seksi.index', compact('seksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seksi'         => 'required|string|max:100',
            'seksi_singkat' => 'required|string|max:50',
            'nama_kepala'   => 'required|string|max:100',
            'nip_kepala'    => 'nullable|string|max:30'
        ]);

        Seksi::create($request->all());

        return back()->with('success', 'Data seksi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $seksi = Seksi::findOrFail($id);

        $request->validate([
            'seksi'         => 'required|string|max:100',
            'seksi_singkat' => 'required|string|max:50',
            'nama_kepala'   => 'required|string|max:100',
            'nip_kepala'    => 'nullable|string|max:30',
        ]);

        $seksi->update($request->all());

        return back()->with('success', 'Data seksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Seksi::findOrFail($id)->delete();
        return back()->with('success', 'Data seksi berhasil dihapus.');
    }
}
