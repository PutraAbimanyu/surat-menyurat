<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifikasiSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Membuat query dasar untuk model Surat
        $queryVerifikasiSurat = Surat::where('diverifikasi', null)->whereIn('tipe_surat', ['Surat Keluar', 'Surat Disposisi']);
        // Filter berdasarkan parameter pencarian (jika ada)
        if (request('cari')) {
            $queryVerifikasiSurat->where(function ($query) {
                // Pencarian berdasarkan nomor surat, pengirim, atau nomor agenda
                $query->where('nomor_surat', 'like', '%' . request('cari') . '%')
                    ->orWhere('pengirim', 'like', '%' . request('cari') . '%')
                    ->orWhere('nomor_agenda', 'like', '%' . request('cari') . '%');
            });
        }

        // Filter berdasarkan klasifikasi surat jika parameter tersedia
        if (($requestTipeSurat = request('tipe-surat')) && $requestTipeSurat !== 'semua') {
            $queryVerifikasiSurat->where('tipe_surat', $requestTipeSurat);
        }

        // Ambil hasil query, urutkan dari yang terbaru, dan paginasi sebanyak 10 item per halaman
        $daftarVerifikasiSurat = $queryVerifikasiSurat->latest()->paginate(10);

        return view('pages.verifikasi-surat.index', [
            'title' => 'Verifikasi Surat',
            'daftarVerifikasiSurat' => $daftarVerifikasiSurat,
            'daftarTipeSurat' => ['Surat Keluar', 'Surat Disposisi']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        // Mencari verifikasi surat berdasarkan ID
        $verifikasiSurat = Surat::find($id);

        // Jika surat diverifikasi
        if ($request->diverifikasi == "true") {
            $verifikasiSurat->update([
                'diverifikasi' => true
            ]);
            return redirect()->route('verifikasi-surat.index')
                ->with('berhasil', 'Surat berhasil diverifikasi.');
        }
        // Jika surat tidak diverifikasi
        else {
            $verifikasiSurat->update([
                'diverifikasi' => false
            ]);
            // Redirect ke halaman index dengan pesan sukses
            return redirect()->route('verifikasi-surat.index')
                ->with('berhasil', 'Surat berhasil ditolak.');
        }
    }
}
