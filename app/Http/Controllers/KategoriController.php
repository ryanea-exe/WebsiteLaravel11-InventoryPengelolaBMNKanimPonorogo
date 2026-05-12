<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('nama')->get();
        return view('pages.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
        ]);

        Kategori::create($request->all());

        return back()->with('success', 'Data kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama'        => 'required|string|max:100',
        ]);

        $kategori->update($request->all());

        return back()->with('success', 'Data kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();
        return back()->with('success', 'Data kategori berhasil dihapus.');
    }
}
