<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaStafController extends Controller
{

    public function index()
    {
        // Membuat query dasar untuk model Surat
        $queryUserStaf = User::where('peran_id', Peran::PERAN_STAF_ID);

        // Filter berdasarkan parameter pencarian (jika ada)
        if (request('cari')) {
            $queryUserStaf->where(function ($query) {
                // Pencarian berdasarkan nama atau username
                $query->where('nama', 'like', '%' . request('cari') . '%')
                    ->orWhere('username', 'like', '%' . request('cari') . '%');
            });
        }

        // Filter berdasarkan status akun jika parameter tersedia
        if (($requestStatusAkun = request('akun-nonaktif'))) {
            // Melakukan pencarian query berdasarkan status akun
            $queryUserStaf->where(
                'akun_nonaktif',
                // Mengubah string menjadi boolean
                filter_var($requestStatusAkun, FILTER_VALIDATE_BOOLEAN)
            );
        }
        // Jika tidak ada parameter pencarian status akun
        else {
            // Melakukan pencarian query akun nonaktif false/akub aktif
            $queryUserStaf->where('akun_nonaktif', false);
        }

        // Ambil hasil query, urutkan dari yang terbaru, dan paginasi sebanyak 10 item per halaman
        $daftarUserStaf = $queryUserStaf->latest()->paginate(10);

        // Kembalikan view dengan data user staf
        return view('pages.kelola-staf.index', [
            'title' => 'Daftar Staf', // Judul halaman
            'daftarUserStaf' => $daftarUserStaf, // Data user staf yang difilter dan dipaginasi
        ]);
    }

    public function create()
    {
        // Menampilkan view untuk form tambah user staf
        return view('pages.kelola-staf.form', [
            'title' => 'Tambah Staf', // Judul halaman/form
            // Mengirimkan instance baru dari model Surat sebagai data default form (kosong)
            'userStaf' => new User(),
            // Informasi route untuk form submission
            'route' => [
                'method' => 'POST', // Metode HTTP yang digunakan untuk menyimpan data
                'action' => route('kelola-staf.store') // URL aksi form untuk menyimpan user staf
            ],
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input dari form user staf
        $validatedUserStaf = $request->validate([
            'nama' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'username' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'password' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'password_confirmation' => 'required|max:255|string|same:password', // Wajib diisi, max 255 karakter
        ]);

        // Simpan data user staf ke database
        User::create([
            'peran_id' => Peran::PERAN_STAF_ID, // Peran di-set langsung sebagai 'Staf'
            'nama' => $validatedUserStaf['nama'],
            'username' => $validatedUserStaf['username'],
            'password' => Hash::make($validatedUserStaf['password']),
        ]);

        // Redirect ke halaman index user staf dengan pesan sukses
        return redirect()->route('kelola-staf.index')->with('berhasil', 'User staf berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        // Mencari user staf berdasarkan ID
        $userStaf = User::with('surat')->find($id);

        // Menampilkan halaman detail user staf
        return view('pages.kelola-staf.detail', [
            'title' => 'Detail Staf', // Judul halaman
            'userStaf' => $userStaf // Data user staf yang akan ditampilkan
        ]);
    }

    public function edit(String $id)
    {
        // Mencari data user staf berdasarkan ID
        $userStaf = User::find($id);

        // Menampilkan form edit user staf
        return view('pages.kelola-staf.form', [
            'title' => 'Edit Staf', // Judul halaman/form
            // Data user staf yang akan diedit (akan diprefill ke dalam form)
            'userStaf' => $userStaf,
            // Informasi route dan method untuk form submission
            'route' => [
                'method' => 'PUT', // HTTP method untuk update
                'action' => route('kelola-staf.update', $userStaf->id) // URL aksi form
            ],
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Mencari user staf berdasarkan ID
        $userStaf = User::find($id);

        // Validasi input dari form user staf
        $validatedUserStaf = $request->validate([
            'nama' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'username' => ['required', 'max:255', 'string', Rule::unique('users')->ignore($userStaf->id)], // max 255 karakter
        ]);

        // Update data user staf ke database
        $userStaf->update([
            'nama' => $validatedUserStaf['nama'],
            'username' => $validatedUserStaf['username'],
        ]);

        if ($request->password) {
            $validatedUserStaf[] = $request->validate([
                'password' => 'required|max:255|string', // Wajib diisi, max 255 karakter
                'password_confirmation' => 'required|max:255|string|same:password', // Wajib diisi, max 255 karakter
            ]);

            $userStaf->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Redirect ke halaman index user staf dengan pesan sukses
        return redirect()->route('kelola-staf.index')->with('berhasil', 'User staf berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Mencari user staf berdasarkan ID
        $userStaf = User::find($id);

        // Melakukan status nonaktif data user staf dari database
        $userStaf->update(
            [
                // Jika data user staf sedang aktif, maka nonaktifkan, sebaliknya aktifkan
                'akun_nonaktif' => $userStaf->akun_nonaktif ? false : true
            ]
        );

        return redirect()->route('kelola-staf.index')->with('berhasil', "User staf $userStaf->nama berhasil dinonaktifkan.");
    }
}
