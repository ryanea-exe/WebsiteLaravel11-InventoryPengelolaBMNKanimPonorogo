<?php

namespace App\Http\Controllers;

use App\Models\Setting2;
use Illuminate\Http\Request;

class Setting2Controller extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'nama_kasubbag_tu' => 'nullable|string|max:255',
            'nip_kasubbag_tu'  => 'nullable|string|max:50',
            'nama_kaurumum_tu' => 'nullable|string|max:255',
            'nip_kaurumum_tu'  => 'nullable|string|max:50',
            'nama_staffbmn_tu' => 'nullable|string|max:255',
            'nip_staffbmn_tu'  => 'nullable|string|max:50',
        ]);

        $setting2 = Setting2::first();

        if (!$setting2) {
            $setting2 = new Setting2();
        }

        $setting2->nama_kasubbag_tu = $request->nama_kasubbag_tu;
        $setting2->nip_kasubbag_tu = $request->nip_kasubbag_tu;
        $setting2->nama_kaurumum_tu = $request->nama_kaurumum_tu;
        $setting2->nip_kaurumum_tu = $request->nip_kaurumum_tu;
        $setting2->nama_staffbmn_tu = $request->nama_staffbmn_tu;
        $setting2->nip_staffbmn_tu = $request->nip_staffbmn_tu;

        $setting2->save();

        return redirect()
            ->route('setting.index', ['tab' => 'penandatangan'])
            ->with('success_penandatangan', 'Pengaturan Penandatangan berhasil diperbarui');
    }
}
