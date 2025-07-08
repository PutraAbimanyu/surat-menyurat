@extends('layouts.app')

@section('content')
    <x-button.back />
    <div class="grid gap-3 mt-4">
        <div class="grid gap-3 lg:grid-cols-3">
            <div>
                <h1>Nomor Surat:</h1>
                <strong>{{ $suratMasuk->nomor_surat }}</strong>
            </div>
            <div>
                <h1>Pengirim:</h1>
                <strong>{{ $suratMasuk->pengirim }}</strong>
            </div>
            <div>
                <h1>Nomor Agenda:</h1>
                <strong>{{ $suratMasuk->nomor_agenda }}</strong>
            </div>
        </div>
        <div>
            <h1>Keterangan:</h1>
            <strong>{{ $suratMasuk->keterangan ?? 'Tidak ada keterangan.' }}</strong>
        </div>
        <div class="grid gap-3 lg:grid-cols-3">
            <div>
                <h1>Pengirim:</h1>
                <strong>{{ $suratMasuk->klasifikasiSurat->nama_klasifikasi }}</strong>
            </div>
            <div class="col-span-2">
                <h1>Lampiran:</h1>
                <a class="text-blue-500 hover:underline" target="_blank" href="{{ '/storage/' . $suratMasuk->lampiran }}">
                    {{ 'storage/' . $suratMasuk->lampiran }}
                </a>
            </div>
        </div>
    </div>
@endsection
