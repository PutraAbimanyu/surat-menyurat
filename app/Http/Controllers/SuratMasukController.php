<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{

    public function index()
    {
        // Membuat query dasar untuk model Surat
        $querySuratMasuk = Surat::with(['klasifikasiSurat', 'user'])->where('tipe_surat', 'Surat Masuk');

        // Filter berdasarkan parameter pencarian (jika ada)
        if (request('cari')) {
            $querySuratMasuk->where(function ($query) {
                // Pencarian berdasarkan nomor surat, pengirim, atau nomor agenda
                $query->where('nomor_surat', 'like', '%' . request('cari') . '%')
                    ->orWhere('pengirim', 'like', '%' . request('cari') . '%')
                    ->orWhere('nomor_agenda', 'like', '%' . request('cari') . '%');
            });
        }

        // Filter berdasarkan klasifikasi surat jika parameter tersedia
        if (($requestKlasifikasiSuratId = request('klasifikasi-surat-id')) && $requestKlasifikasiSuratId !== 'semua') {
            $querySuratMasuk->where('klasifikasi_surat_id', $requestKlasifikasiSuratId);
        }

        // Ambil hasil query, urutkan dari yang terbaru, dan paginasi sebanyak 10 item per halaman
        $daftarSuratMasuk = $querySuratMasuk->latest()->paginate(10);

        // Kembalikan view dengan data surat masuk dan daftar klasifikasi surat
        return view('pages.surat-masuk.index', [
            'title' => 'Surat Masuk', // Judul halaman
            'daftarSuratMasuk' => $daftarSuratMasuk, // Data surat masuk yang difilter dan dipaginasi
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all() // Semua data klasifikasi surat untuk dropdown/filter
        ]);
    }

    public function create()
    {
        // Menampilkan view untuk form tambah surat masuk
        return view('pages.surat-masuk.form', [
            'title' => 'Tambah Surat Masuk', // Judul halaman/form
            // Mengirimkan instance baru dari model Surat sebagai data default form (kosong)
            'suratMasuk' => new Surat(),
            // Informasi route untuk form submission
            'route' => [
                'method' => 'POST', // Metode HTTP yang digunakan untuk menyimpan data
                'action' => route('surat-masuk.store') // URL aksi form untuk menyimpan surat masuk
            ],
            // Mengirim semua data klasifikasi surat untuk digunakan dalam select/dropdown
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all()
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input dari form surat masuk
        $validatedSuratMasuk = $request->validate([
            'nomor_surat' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'pengirim' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'nomor_agenda' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'tanggal_surat' => 'required|date', // Harus berupa tanggal
            'tanggal_diterima' => 'required|date', // Harus berupa tanggal
            'keterangan' => 'nullable', // Opsional
            'klasifikasi_surat_id' => 'required|exists:klasifikasi_surat,id', // Wajib, dan harus ada di tabel klasifikasi_surat
            'lampiran' => 'required|file|mimes:pdf,doc,docx' // Wajib berupa file dengan tipe tertentu
        ]);

        // Simpan file lampiran ke folder 'surat-masuk' di storage dan ambil path-nya
        $validatedSuratMasuk['lampiran'] = $validatedSuratMasuk['lampiran']->store('surat-masuk');

        // Simpan data surat masuk ke database
        Surat::create([
            'user_id' => Auth::user()->id, // Tipe surat di-set langsung sebagai 'Surat Masuk'
            'tipe_surat' => Surat::SURAT_MASUK, // Tipe surat di-set langsung sebagai 'Surat Masuk'
            'nomor_surat' => $validatedSuratMasuk['nomor_surat'],
            'pengirim' => $validatedSuratMasuk['pengirim'],
            'nomor_agenda' => $validatedSuratMasuk['nomor_agenda'],
            'tanggal_surat' => $validatedSuratMasuk['tanggal_surat'],
            'tanggal_diterima' => $validatedSuratMasuk['tanggal_diterima'],
            'keterangan' => $validatedSuratMasuk['keterangan'],
            'klasifikasi_surat_id' => $validatedSuratMasuk['klasifikasi_surat_id'],
            'lampiran' => $validatedSuratMasuk['lampiran']
        ]);

        // Redirect ke halaman index surat masuk dengan pesan sukses
        return redirect()->route('surat-masuk.index')
            ->with('berhasil', 'Surat masuk berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        // Mencari surat masuk berdasarkan ID
        $suratMasuk = Surat::find($id);

        // Menampilkan halaman detail surat masuk
        return view('pages.surat-masuk.detail', [
            'title' => 'Detail Surat Masuk', // Judul halaman
            'suratMasuk' => $suratMasuk // Data surat masuk yang akan ditampilkan
        ]);
    }

    public function edit(string $id)
    {
        // Mencari data surat masuk berdasarkan ID
        $suratMasuk = Surat::find($id);

        // Menampilkan form edit surat masuk
        return view('pages.surat-masuk.form', [
            'title' => 'Edit Surat Masuk', // Judul halaman/form

            // Data surat masuk yang akan diedit (akan diprefill ke dalam form)
            'suratMasuk' => $suratMasuk,

            // Informasi route dan method untuk form submission
            'route' => [
                'method' => 'PUT', // HTTP method untuk update
                'action' => route('surat-masuk.update', $suratMasuk->id) // URL aksi form
            ],

            // Semua data klasifikasi surat untuk dropdown/select di form
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all()
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi data yang dikirim dari form edit
        $validatedSuratMasuk = $request->validate([
            'nomor_surat' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'pengirim' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'nomor_agenda' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'tanggal_surat' => 'required|date', // Wajib, harus format tanggal
            'tanggal_diterima' => 'required|date', // Wajib, harus format tanggal
            'keterangan' => 'nullable', // Opsional
            'klasifikasi_surat_id' => 'required|exists:klasifikasi_surat,id', // Harus ada di tabel klasifikasi_surat
            'lampiran' => 'file|mimes:pdf,doc,docx' // Opsional, jika ada harus file dengan format tertentu
        ]);

        // Mencari surat masuk berdasarkan ID
        $suratMasuk = Surat::find($id);

        // Jika ada file lampiran baru yang diunggah
        if ($request->file('lampiran')) {
            // Hapus lampiran lama dari storage
            Storage::delete($suratMasuk->lampiran);

            // Simpan lampiran baru dan perbarui path-nya
            $validatedSuratMasuk['lampiran'] = $validatedSuratMasuk['lampiran']->store('surat-masuk');
        }

        // Update data surat masuk ke database
        $suratMasuk->update($validatedSuratMasuk);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('surat-masuk.index')
            ->with('berhasil', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Mencari surat masuk berdasarkan ID
        $suratMasuk = Surat::find($id);

        // Menghapus file lampiran dari storage
        Storage::delete($suratMasuk->lampiran);

        // Menghapus data surat masuk dari database
        $suratMasuk->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('surat-masuk.index')
            ->with('berhasil', 'Surat masuk berhasil dihapus.');
    }
}
