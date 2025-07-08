<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiSurat;
use App\Models\Peran;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KlasifikasiSuratController extends Controller
{

    public function index()
    {
        // Membuat query dasar untuk model Klasifikasi Surat
        $queryKlasifikasiSurat = KlasifikasiSurat::query();

        // Filter berdasarkan parameter pencarian (jika ada)
        if (request('cari')) {
            $queryKlasifikasiSurat->where(function ($query) {
                // Pencarian berdasarkan nama klasifikasi
                $query->where('nama_klasifikasi', 'like', '%' . request('cari') . '%');
            });
        }

        // Ambil hasil query, urutkan dari yang terbaru, dan paginasi sebanyak 10 item per halaman
        $daftarKlasifikasiSurat = $queryKlasifikasiSurat->paginate(10);

        // Kembalikan view dengan data surat masuk dan daftar klasifikasi surat
        return view('pages.klasifikasi-surat.index', [
            'title' => 'Klasifikasi Surat', // Judul halaman
            'daftarKlasifikasiSurat' => $daftarKlasifikasiSurat // Semua data klasifikasi surat
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan view untuk form tambah klasifikasi surat
        return view('pages.klasifikasi-surat.form', [
            'title' => 'Tambah Klasifikasi Surat', // Judul halaman
            // Mengirimkan instance baru dari model Klasifikasi Surat sebagai data default form (kosong)
            'klasifikasiSurat' => new KlasifikasiSurat(),
            // Informasi route untuk form submission
            'route' => [
                'method' => 'POST', // Metode HTTP yang digunakan untuk menyimpan data
                'action' => route('klasifikasi-surat.store') // URL aksi form untuk menyimpan klasifikasi surat
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form klasifikasi surat
        $validatedKlasifikasiSurat = $request->validate([
            'nama_klasifikasi' => 'required|max:255|string|unique:klasifikasi_surat,nama_klasifikasi', // Wajib diisi, max 255 karakter
        ]);

        // Simpan data klasifikasi surat ke database
        KlasifikasiSurat::create([
            'nama_klasifikasi' => $validatedKlasifikasiSurat['nama_klasifikasi'],
        ]);

        // Redirect ke halaman index klasifikasi surat dengan pesan sukses
        return redirect()->route('klasifikasi-surat.index')
            ->with('berhasil', 'Klasifikasi surat berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        // Mencari klasifikasi surat berdasarkan ID
        $klasifikasiSurat = KlasifikasiSurat::find($id);

        // Menampilkan halaman detail klasifikasi surat
        return view('pages.klasifikasi-surat.detail', [
            'title' => 'Detail Surat Masuk', // Judul halaman
            'klasifikasiSurat' => $klasifikasiSurat // Data klasifikasi surat yang akan ditampilkan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Mencari data klasifikasi surat berdasarkan ID
        $klasifikasiSurat = KlasifikasiSurat::find($id);

        // Menampilkan form edit klasifikasi surat
        return view('pages.klasifikasi-surat.form', [
            'title' => 'Edit Klasifikasi Surat', // Judul halaman/form
            // Data klasifikasi surat yang akan diedit (akan diprefill ke dalam form)
            'klasifikasiSurat' => $klasifikasiSurat,
            // Informasi route dan method untuk form submission
            'route' => [
                'method' => 'PUT', // HTTP method untuk update
                'action' => route('klasifikasi-surat.update', $klasifikasiSurat->id) // URL aksi form
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data yang dikirim dari form edit
        $validatedKlasifikasiSurat = $request->validate([
            'nama_klasifikasi' => 'required|max:255|string', // Wajib, maksimal 255 karakter
        ]);

        // Mencari klasifikasi surat berdasarkan ID
        $klasifikasiSurat = KlasifikasiSurat::find($id);

        // Update data klasifikasi surat ke database
        $klasifikasiSurat->update($validatedKlasifikasiSurat);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('klasifikasi-surat.index')
            ->with('berhasil', 'Klasifikasi surat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Mencari klasifikasi surat berdasarkan ID
        $klasifikasiSurat = KlasifikasiSurat::find($id);

        // Jika klasifikasi surat memiliki relasi dengan surat
        if ($klasifikasiSurat->surat()->exists()) {
            // Redirect ke halaman index dengan pesan gagal
            return redirect()->route('klasifikasi-surat.index')
                ->with('gagal', 'Data tidak bisa dihapus karena masih memiliki relasi, mohon hapus surat yang berkaitan terlebih dahulu.');
        }

        // Menghapus data klasifikasi surat dari database
        $klasifikasiSurat->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('klasifikasi-surat.index')
            ->with('berhasil', 'Klasifikasi surat berhasil dihapus.');
    }
}
