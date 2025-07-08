<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratDisposisiController extends Controller
{

    public function index()
    {
        // Membuat query dasar untuk model Surat
        $querySuratDisposisi = Surat::where('tipe_surat', 'Surat Disposisi');

        // Filter berdasarkan parameter pencarian (jika ada)
        if (request('cari')) {
            $querySuratDisposisi->where(function ($query) {
                // Pencarian berdasarkan nomor surat, pengirim, atau nomor agenda
                $query->where('nomor_surat', 'like', '%' . request('cari') . '%')
                    ->orWhere('pengirim', 'like', '%' . request('cari') . '%')
                    ->orWhere('nomor_agenda', 'like', '%' . request('cari') . '%');
            });
        }

        // Filter berdasarkan klasifikasi surat jika parameter tersedia
        if (($requestKlasifikasiSuratId = request('klasifikasi-surat-id')) && $requestKlasifikasiSuratId !== 'semua') {
            $querySuratDisposisi->where('klasifikasi_surat_id', $requestKlasifikasiSuratId);
        }

        // Ambil hasil query, urutkan dari yang terbaru, dan paginasi sebanyak 10 item per halaman
        $daftarSuratDisposisi = $querySuratDisposisi->latest()->paginate(10);

        // Kembalikan view dengan data surat disposisi dan daftar klasifikasi surat
        return view('pages.surat-disposisi.index', [
            'title' => 'Surat Disposisi', // Judul halaman
            'daftarSuratDisposisi' => $daftarSuratDisposisi, // Data surat disposisi yang difilter dan dipaginasi
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all() // Semua data klasifikasi surat untuk dropdown/filter
        ]);
    }

    public function create()
    {
        // Menampilkan view untuk form tambah surat disposisi
        return view('pages.surat-disposisi.form', [
            'title' => 'Tambah Surat Disposisi', // Judul halaman/form
            // Mengirimkan instance baru dari model Surat sebagai data default form (kosong)
            'suratDisposisi' => new Surat(),
            // Informasi route untuk form submission
            'route' => [
                'method' => 'POST', // Metode HTTP yang digunakan untuk menyimpan data
                'action' => route('surat-disposisi.store') // URL aksi form untuk menyimpan surat disposisi
            ],
            // Mengirim semua data klasifikasi surat untuk digunakan dalam select/dropdown
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all()
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input dari form surat disposisi
        $validatedSuratDisposisi = $request->validate([
            'nomor_surat' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'pengirim' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'nomor_agenda' => 'required|max:255|string', // Wajib diisi, max 255 karakter
            'tanggal_surat' => 'required|date', // Harus berupa tanggal
            'tanggal_diterima' => 'required|date', // Harus berupa tanggal
            'keterangan' => 'nullable', // Opsional
            'klasifikasi_surat_id' => 'required|exists:klasifikasi_surat,id', // Wajib, dan harus ada di tabel klasifikasi_surat
            'lampiran' => 'required|file|mimes:pdf,doc,docx' // Wajib berupa file dengan tipe tertentu
        ]);

        // Simpan file lampiran ke folder 'surat-disposisi' di storage dan ambil path-nya
        $validatedSuratDisposisi['lampiran'] = $validatedSuratDisposisi['lampiran']->store('surat-disposisi');

        // Simpan data surat disposisi ke database
        Surat::create([
            'user_id' => Auth::user()->id, // Tipe surat di-set langsung sebagai 'Surat Masuk'
            'tipe_surat' => 'Surat Disposisi', // Tipe surat di-set langsung sebagai 'Surat Disposisi'
            'nomor_surat' => $validatedSuratDisposisi['nomor_surat'],
            'pengirim' => $validatedSuratDisposisi['pengirim'],
            'nomor_agenda' => $validatedSuratDisposisi['nomor_agenda'],
            'tanggal_surat' => $validatedSuratDisposisi['tanggal_surat'],
            'tanggal_diterima' => $validatedSuratDisposisi['tanggal_diterima'],
            'keterangan' => $validatedSuratDisposisi['keterangan'],
            'klasifikasi_surat_id' => $validatedSuratDisposisi['klasifikasi_surat_id'],
            'lampiran' => $validatedSuratDisposisi['lampiran']
        ]);

        // Redirect ke halaman index surat disposisi dengan pesan sukses
        return redirect()->route('surat-disposisi.index')
            ->with('berhasil', 'Surat disposisi berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        // Mencari surat disposisi berdasarkan ID
        $suratDisposisi = Surat::find($id);

        // Menampilkan halaman detail surat disposisi
        return view('pages.surat-disposisi.detail', [
            'title' => 'Detail Surat Disposisi', // Judul halaman
            'suratDisposisi' => $suratDisposisi // Data surat disposisi yang akan ditampilkan
        ]);
    }

    public function edit(string $id)
    {
        // Mencari data surat disposisi berdasarkan ID
        $suratDisposisi = Surat::find($id);

        // Menampilkan form edit surat disposisi
        return view('pages.surat-disposisi.form', [
            'title' => 'Edit Surat Disposisi', // Judul halaman/form

            // Data surat disposisi yang akan diedit (akan diprefill ke dalam form)
            'suratDisposisi' => $suratDisposisi,

            // Informasi route dan method untuk form submission
            'route' => [
                'method' => 'PUT', // HTTP method untuk update
                'action' => route('surat-disposisi.update', $suratDisposisi->id) // URL aksi form
            ],

            // Semua data klasifikasi surat untuk dropdown/select di form
            'daftarKlasifikasiSurat' => KlasifikasiSurat::all()
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi data yang dikirim dari form edit
        $validatedSuratDisposisi = $request->validate([
            'nomor_surat' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'pengirim' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'nomor_agenda' => 'required|max:255|string', // Wajib, maksimal 255 karakter
            'tanggal_surat' => 'required|date', // Wajib, harus format tanggal
            'tanggal_diterima' => 'required|date', // Wajib, harus format tanggal
            'keterangan' => 'nullable', // Opsional
            'klasifikasi_surat_id' => 'required|exists:klasifikasi_surat,id', // Harus ada di tabel klasifikasi_surat
            'lampiran' => 'file|mimes:pdf,doc,docx' // Opsional, jika ada harus file dengan format tertentu
        ]);

        // Mencari surat disposisi berdasarkan ID
        $suratDisposisi = Surat::find($id);

        // Jika ada file lampiran baru yang diunggah
        if ($request->file('lampiran')) {
            // Hapus lampiran lama dari storage
            Storage::delete($suratDisposisi->lampiran);

            // Simpan lampiran baru dan perbarui path-nya
            $validatedSuratDisposisi['lampiran'] = $validatedSuratDisposisi['lampiran']->store('surat-disposisi');
        }

        // Update data surat disposisi ke database
        $suratDisposisi->update($validatedSuratDisposisi);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('surat-disposisi.index')
            ->with('berhasil', 'Surat disposisi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Mencari surat disposisi berdasarkan ID
        $suratDisposisi = Surat::find($id);

        // Menghapus file lampiran dari storage
        Storage::delete($suratDisposisi->lampiran);

        // Menghapus data surat disposisi dari database
        $suratDisposisi->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('surat-disposisi.index')
            ->with('berhasil', 'Surat disposisi berhasil dihapus.');
    }
}
