@extends('layouts.app')

@php
    use App\Models\Peran;
@endphp

@section('content')
    {{-- Section ini menggunakan grid dengan 3 kolom dan jarak antar item sebesar 3.
        Pada layar berukuran kecil (max-md), grid akan berubah menjadi 1 kolom. --}}
    <section class="grid lg:grid-cols-3 gap-3">

        <!-- Komponen kartu untuk menampilkan total semua surat -->
        <x-card label="Total Semua Surat" value="{{ $dataCard['totalSemuaSurat'] }}"
            icon="<i class='bxr bxs-envelope-alt'></i>" />

        <!-- Komponen kartu untuk menampilkan total surat masuk -->
        <x-card label="Total Surat Masuk" value="{{ $dataCard['totalSuratMasuk'] }}"
            icon="<i class='bxr bxs-mail-open'></i>" />

        <!-- Komponen kartu untuk menampilkan total surat keluar -->
        <x-card label="Total Surat Keluar" value="{{ $dataCard['totalSuratKeluar'] }}"
            icon="<i class='bxr  bxs-envelope-open'></i>" />

        <!-- Komponen kartu untuk menampilkan total surat keluar -->
        <x-card label="Total Surat Disposisi" value="{{ $dataCard['totalSuratDisposisi'] }}"
            icon="<i class='bxr bxs-envelope'></i> " />

        <!-- Komponen kartu untuk menampilkan verifikasi surat -->
        <x-card label="Total Butuh Verifikasi Surat" value="{{ $dataCard['totalButuhVerifikasiSurat'] }}"
            icon="<i class='bx bx-check'></i>" />

        <!-- Komponen kartu untuk menampilkan total klasifikasi surat -->
        <x-card label="Total Klasifikasi" value="{{ $dataCard['totalKlasifikasiSurat'] }}"
            icon="<i class='bxr bxs-refresh-cw-dot'></i>" />

        @if (Auth::user()->peran_id == Peran::PERAN_ADMIN_ID)
            <!-- Komponen kartu untuk menampilkan total pengguna staf -->
            <x-card label="Total Admin Aktif" value="{{ $dataCard['totalStafAktif'] }}"
                icon="<i class='bxr bxs-user'></i> " />
        @endif

    </section>
@endsection
