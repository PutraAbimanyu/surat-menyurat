@extends('layouts.app')

@section('content')
    <x-button.back />
    <div class="grid gap-3 mt-4">
        <div class="grid gap-3 lg:grid-cols-3">
            <div>
                <h1>Nama Klasifikasi Surat:</h1>
                <strong>{{ $klasifikasiSurat->nama_klasifikasi }}</strong>
            </div>
            <div>
                <h1>Total Relasi:</h1>
                <strong>{{ $klasifikasiSurat->surat()->count() }} Surat</strong>
            </div>
        </div>
    </div>
@endsection
