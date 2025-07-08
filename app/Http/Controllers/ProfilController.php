<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        return view('pages.profil', [
            'title' => 'Profil',
            'user' => Auth::user()
        ]);
    }

    public function perbaruiAkun(Request $request)
    {
        $user = Auth::user();

        if ($request->nama) {
            $request->validate([
                'nama' => 'max:255',
            ]);
            $user->update([
                'nama' => $request->nama,
            ]);
        }

        if ($request->username) {
            $request->validate([
                'username' => [
                    'max:255',
                    Rule::unique('users', 'username')->ignore($user->id)
                ],
            ]);
            $user->update([
                'username' => $request->username,
            ]);
        }

        if ($request->password) {
            $request->validate([
                'password' => 'min:8|max:255',
                'password_confirmation' => 'same:password',
            ]);
            $user->update([
                'password' => $request->password
            ]);
        }

        return redirect()->route('profil.index')->with('berhasil', 'Akun berhasil diperbarui');
    }
}
