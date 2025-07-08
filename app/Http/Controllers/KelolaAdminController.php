<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaAdminController extends Controller
{
    public function index()
    {
        // Membuat query dasar untuk model Surat
        $queryUserAdmin = User::where('peran_id', Peran::PERAN_ADMIN_ID);

        // Filter berdasarkan parameter pencarian (jika ada)
        if (request('cari')) {
            $queryUserAdmin->where(function ($query) {
                // Pencarian berdasarkan nama atau username
                $query->where('nama', 'like', '%' . request('cari') . '%')
                    ->orWhere('username', 'like', '%' . request('cari') . '%');
            });
        }

        // Filter berdasarkan status akun jika parameter tersedia
        if (($requestStatusAkun = request('akun-nonaktif'))) {
            // Melakukan pencarian query berdasarkan status akun
            $queryUserAdmin->where(
                'akun_nonaktif',
                // Mengubah string menjadi boolean
                filter_var($requestStatusAkun, FILTER_VALIDATE_BOOLEAN)
            );
        }
        // Jika tidak ada parameter pencarian status akun
        else {
            // Melakukan pencarian query akun nonaktif false/akub aktif
            $queryUserAdmin->where('akun_nonaktif', false);
        }

        // Ambil hasil query, urutkan dari yang terbaru, dan paginasi sebanyak 10 item per halaman
        $daftarUserAdmin = $queryUserAdmin->latest()->paginate(10);

        // Kembalikan view dengan data user admin
        return view('pages.kelola-admin.index', [
            'title' => 'Daftar Admin', // Judul halaman
            'daftarUserAdmin' => $daftarUserAdmin, // Data user admin yang difilter dan dipaginasi
        ]);
    }

    public function create()
    {
        // Menampilkan view untuk form tambah user admin
        return view('pages.kelola-admin.form', [
            'title' => 'Tambah Admin', // Judul halaman/form
            // Mengirimkan instance baru dari model Surat sebagai data default form (kosong)
            'userAdmin' => new User(),
            // Informasi route untuk form submission
            'route' => [
                'method' => 'POST', // Metode HTTP yang digunakan untuk menyimpan data
                'action' => route('kelola-admin.store') // URL aksi form untuk menyimpan user admin
            ],
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input dari form user admin
        $validatedUserAdmin = $request->validate([
            'nama' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'username' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'password' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'password_confirmation' => 'required|max:255|string|same:password', // Wajib diisi, max 255 karakter
        ]);

        // Simpan data user admin ke database
        User::create([
            'peran_id' => Peran::PERAN_ADMIN_ID, // Peran di-set langsung sebagai 'Admin'
            'nama' => $validatedUserAdmin['nama'],
            'username' => $validatedUserAdmin['username'],
            'password' => Hash::make($validatedUserAdmin['password']),
        ]);

        // Redirect ke halaman index user admin dengan pesan sukses
        return redirect()->route('kelola-admin.index')->with('berhasil', 'User admin berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        // Mencari user admin berdasarkan ID
        $userAdmin = User::with('surat')->find($id);

        // Menampilkan halaman detail user admin
        return view('pages.kelola-admin.detail', [
            'title' => 'Detail Admin', // Judul halaman
            'userAdmin' => $userAdmin // Data user admin yang akan ditampilkan
        ]);
    }

    public function edit(String $id)
    {
        // Mencari data user admin berdasarkan ID
        $userAdmin = User::find($id);

        // Menampilkan form edit user admin
        return view('pages.kelola-admin.form', [
            'title' => 'Edit Admin', // Judul halaman/form
            // Data user admin yang akan diedit (akan diprefill ke dalam form)
            'userAdmin' => $userAdmin,
            // Informasi route dan method untuk form submission
            'route' => [
                'method' => 'PUT', // HTTP method untuk update
                'action' => route('kelola-admin.update', $userAdmin->id) // URL aksi form
            ],
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Mencari user admin berdasarkan ID
        $userAdmin = User::find($id);

        // Validasi input dari form user admin
        $validatedUserAdmin = $request->validate([
            'nama' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'username' => ['required', 'max:255', 'string', Rule::unique('users')->ignore($userAdmin->id)], // max 255 karakter
        ]);

        // Update data user admin ke database
        $userAdmin->update([
            'nama' => $validatedUserAdmin['nama'],
            'username' => $validatedUserAdmin['username'],
        ]);

        if ($request->password) {
            $validatedUserAdmin[] = $request->validate([
                'password' => 'required|max:255|string', // Wajib diisi, max 255 karakter
                'password_confirmation' => 'required|max:255|string|same:password', // Wajib diisi, max 255 karakter
            ]);

            $userAdmin->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Redirect ke halaman index user admin dengan pesan sukses
        return redirect()->route('kelola-admin.index')->with('berhasil', 'User admin berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Mencari user admin berdasarkan ID
        $userAdmin = User::find($id);

        // Melakukan status nonaktif data user admin dari database
        $userAdmin->update(
            [
                // Jika data user admin sedang aktif, maka nonaktifkan, sebaliknya aktifkan
                'akun_nonaktif' => $userAdmin->akun_nonaktif ? false : true
            ]
        );

        return redirect()->route('kelola-admin.index')->with('berhasil', "User admin $userAdmin->nama berhasil dinonaktifkan.");
    }
}
