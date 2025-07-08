<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KlasifikasiSurat;
use App\Models\Peran;
use App\Models\Surat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard', [
            'title' => 'Dashboard',
            'dataCard' => [
                'totalSemuaSurat' => Surat::count(),
                'totalSuratMasuk' => Surat::where('tipe_surat', 'Surat Masuk')->count(),
                'totalSuratKeluar' => Surat::where('tipe_surat', 'Surat Keluar')->count(),
                'totalSuratDisposisi' => Surat::where('tipe_surat', 'Surat Disposisi')->count(),
                'totalKlasifikasiSurat' => KlasifikasiSurat::count(),
                'totalStafAktif' => User::where('peran_id', Peran::PERAN_ADMIN_ID)->where('akun_nonaktif', false)->count(),
                'totalButuhVerifikasiSurat' => Surat::where('diverifikasi', null)->count()
            ]
        ]);
    }
}
