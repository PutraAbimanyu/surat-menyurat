<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{

    public function index()
    {
        // Membuat query dasar untuk model Surat
        $querySuratKeluar = Surat::where('tipe_surat', 'Surat Keluar');

        // Filter berdasarkan parameter pencarian (jika ada)
        if (request('cari')) {
            $querySuratKeluar->where(function ($query) {
                // Pencarian berdasarkan nomor surat, pengirim, atau nomor agenda
                $query->where('nomor_surat', 'like', '%' . request('cari') . '%')
                    ->orWhere('pengirim', 'like', '%' . request('cari') . '%')
                    ->orWhere('nomor_agenda', 'like', '%' . request('cari') . '%');
            });
        }

        // Filter berdasarkan klasifikasi surat jika parameter tersedia
        if (($requestKlasifikasiSuratId = request('klasifikasi-surat-id')) && $requestKlasifikasiSuratId !== 'semua') {
            $querySuratKeluar->where('klasifikasi_surat_id', $requestKlasifikasiSuratId);
        }

        // Ambil hasil query, urutkan dari yang terbaru, dan paginasi sebanyak 10 item per halaman
        $daftarSuratKeluar = $querySuratKeluar->latest()->paginate(10);

        // Kembalikan view dengan data surat keluar dan daftar klasifikasi surat
        return view('pages.surat-keluar.index', [
            'title' => 'Surat Keluar', // Judul halaman
            'daftarSuratKeluar' => $daftarSuratKeluar, // Data surat keluar yang difilter dan dipaginasi
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all() // Semua data klasifikasi surat untuk dropdown/filter
        ]);
    }

    public function create()
    {
        // Menampilkan view untuk form tambah surat keluar
        return view('pages.surat-keluar.form', [
            'title' => 'Tambah Surat Keluar', // Judul halaman/form
            // Mengirimkan instance baru dari model Surat sebagai data default form (kosong)
            'suratKeluar' => new Surat(),
            // Informasi route untuk form submission
            'route' => [
                'method' => 'POST', // Metode HTTP yang digunakan untuk menyimpan data
                'action' => route('surat-keluar.store') // URL aksi form untuk menyimpan surat keluar
            ],
            // Mengirim semua data klasifikasi surat untuk digunakan dalam select/dropdown
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all()
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input dari form surat keluar
        $validatedSuratKeluar = $request->validate([
            'nomor_surat' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'pengirim' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'nomor_agenda' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'tanggal_surat' => 'required|date', // Harus berupa tanggal
            'tanggal_diterima' => 'required|date', // Harus berupa tanggal
            'keterangan' => 'nullable', // Opsional
            'klasifikasi_surat_id' => 'required|exists:klasifikasi_surat,id', // Wajib, dan harus ada di tabel klasifikasi_surat
            'lampiran' => 'required|file|mimes:pdf,doc,docx' // Wajib berupa file dengan tipe tertentu
        ]);

        // Simpan file lampiran ke folder 'surat-keluar' di storage dan ambil path-nya
        $validatedSuratKeluar['lampiran'] = $validatedSuratKeluar['lampiran']->store('surat-keluar');

        // Simpan data surat keluar ke database
        Surat::create([
            'user_id' => Auth::user()->id, // Tipe surat di-set langsung sebagai 'Surat Masuk'
            'tipe_surat' => 'Surat Keluar', // Tipe surat di-set langsung sebagai 'Surat Keluar'
            'nomor_surat' => $validatedSuratKeluar['nomor_surat'],
            'pengirim' => $validatedSuratKeluar['pengirim'],
            'nomor_agenda' => $validatedSuratKeluar['nomor_agenda'],
            'tanggal_surat' => $validatedSuratKeluar['tanggal_surat'],
            'tanggal_diterima' => $validatedSuratKeluar['tanggal_diterima'],
            'keterangan' => $validatedSuratKeluar['keterangan'],
            'klasifikasi_surat_id' => $validatedSuratKeluar['klasifikasi_surat_id'],
            'lampiran' => $validatedSuratKeluar['lampiran']
        ]);

        // Redirect ke halaman index surat keluar dengan pesan sukses
        return redirect()->route('surat-keluar.index')
            ->with('berhasil', 'Surat keluar berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        // Mencari surat keluar berdasarkan ID
        $suratKeluar = Surat::find($id);

        // Menampilkan halaman detail surat keluar
        return view('pages.surat-keluar.detail', [
            'title' => 'Detail Surat Keluar', // Judul halaman
            'suratKeluar' => $suratKeluar // Data surat keluar yang akan ditampilkan
        ]);
    }

    public function edit(string $id)
    {
        // Mencari data surat keluar berdasarkan ID
        $suratKeluar = Surat::find($id);

        // Menampilkan form edit surat keluar
        return view('pages.surat-keluar.form', [
            'title' => 'Edit Surat Keluar', // Judul halaman/form

            // Data surat keluar yang akan diedit (akan diprefill ke dalam form)
            'suratKeluar' => $suratKeluar,

            // Informasi route dan method untuk form submission
            'route' => [
                'method' => 'PUT', // HTTP method untuk update
                'action' => route('surat-keluar.update', $suratKeluar->id) // URL aksi form
            ],

            // Semua data klasifikasi surat untuk dropdown/select di form
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all()
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi data yang dikirim dari form edit
        $validatedSuratKeluar = $request->validate([
            'nomor_surat' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'pengirim' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'nomor_agenda' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'tanggal_surat' => 'required|date', // Wajib, harus format tanggal
            'tanggal_diterima' => 'required|date', // Wajib, harus format tanggal
            'keterangan' => 'nullable', // Opsional
            'klasifikasi_surat_id' => 'required|exists:klasifikasi_surat,id', // Harus ada di tabel klasifikasi_surat
            'lampiran' => 'file|mimes:pdf,doc,docx' // Opsional, jika ada harus file dengan format tertentu
        ]);

        // Mencari surat keluar berdasarkan ID
        $suratKeluar = Surat::find($id);

        // Jika ada file lampiran baru yang diunggah
        if ($request->file('lampiran')) {
            // Hapus lampiran lama dari storage
            Storage::delete($suratKeluar->lampiran);

            // Simpan lampiran baru dan perbarui path-nya
            $validatedSuratKeluar['lampiran'] = $validatedSuratKeluar['lampiran']->store('surat-keluar');
        }

        // Update data surat keluar ke database
        $suratKeluar->update($validatedSuratKeluar);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('surat-keluar.index')
            ->with('berhasil', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Mencari surat keluar berdasarkan ID
        $suratKeluar = Surat::find($id);

        // Menghapus file lampiran dari storage
        Storage::delete($suratKeluar->lampiran);

        // Menghapus data surat keluar dari database
        $suratKeluar->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('surat-keluar.index')
            ->with('berhasil', 'Surat keluar berhasil dihapus.');
    }
}
