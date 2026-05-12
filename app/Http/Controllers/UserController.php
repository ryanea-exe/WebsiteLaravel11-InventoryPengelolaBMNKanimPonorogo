<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /* =====================================================
     |  MANAJEMEN USER (ADMIN)
     ===================================================== */
    public function index()
    {
        $users  = User::with('seksi')->orderBy('name')->where('email', '!=', 'superadmin@system.com')->get();
        $seksis = Seksi::orderBy('seksi')->get();

        return view('pages.user.index', compact('users','seksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required',
            'seksi_id' => 'required|exists:seksi,id',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = 'profiles/default-profile.png';

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profiles', 'public');
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'Aktif', // 🔥 OTOMATIS
            'seksi_id' => $request->seksi_id,
            'photo'    => $photoPath,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role'     => 'required',
            'status'   => 'required',
            'seksi_id' => 'required|exists:seksi,id',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update foto jika ada
        if ($request->hasFile('photo')) {
            if (
                $user->photo &&
                $user->photo !== 'profiles/default-profile.png' &&
                Storage::disk('public')->exists($user->photo)
            ) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->photo = $request->file('photo')->store('profiles', 'public');
        }

        $user->name   = $request->name;
        $user->email  = $request->email;
        $user->role   = $request->role;
        $user->status = $request->status;
        $user->seksi_id = $request->seksi_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $ignoreId = $request->ignore_id;

        $exists = User::where('email', $email)
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
        $user = User::findOrFail($id);

        // 🔒 Optional: cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // 🖼️ Hapus foto jika ada kecuali foto default
        if (
            $user->photo &&
            $user->photo !== 'profiles/default-profile.png' &&
            Storage::disk('public')->exists($user->photo)
        ) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /* =====================================================
     |  PROFILE (ADMIN / USER LOGIN)
     ===================================================== */
    // TAMPILKAN HALAMAN EDIT PROFIL
    public function editProfile()
    {
        $users = User::orderBy('name')->get();
        return view('pages.user.edit_profile', compact('users'), [
            'user' => Auth::user()
        ]);
    }

    // UPDATE PROFIL (PASSWORD WAJIB PASSWORD LAMA)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        /* ===============================
        |  KHUSUS UPDATE FOTO SAJA
        =============================== */
        if ($request->hasFile('photo') && !$request->filled('password')) {

            if (
                $user->photo &&
                $user->photo !== 'profiles/default-profile.png' &&
                Storage::disk('public')->exists($user->photo)
            ) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->photo = $request->file('photo')->store('profiles', 'public');
            $user->save();

            return redirect()->route('profile.edit')
                ->with('success', 'Foto profil berhasil diperbarui.');
        }

        /* ===============================
        |  VALIDASI NORMAL
        =============================== */
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'current_password'  => 'nullable|required_with:password|string',
            'password'          => 'nullable|min:8|confirmed',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /* ===============================
        |  CEK PASSWORD LAMA
        =============================== */
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                    ->withInput();
            }
        }

        /* ===============================
        |  UPDATE FOTO (jika sekalian)
        =============================== */
        if ($request->hasFile('photo')) {
            if (
                $user->photo &&
                $user->photo !== 'profiles/default-profile.png' &&
                Storage::disk('public')->exists($user->photo)
            ) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->photo = $request->file('photo')->store('profiles', 'public');
        }

        /* ===============================
        |  UPDATE DATA
        =============================== */
        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
