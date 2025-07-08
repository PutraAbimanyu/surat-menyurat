@extends('layouts.app')

@section('content')
    <x-button.back href="{{ route('klasifikasi-surat.index') }}" />
    <form action="{{ $route['action'] }}" method="POST" enctype="multipart/form-data"
        class="flex bg-white rounded-lg flex-col gap-2 mt-4">
        @csrf
        @method($route['method'])

        <div class="space-y-2">
            <x-text-field name="nama_klasifikasi" value="{{ old('nama_klasifikasi', $klasifikasiSurat->nama_klasifikasi) }}"
                placeholder="Masukkan klasifikasi surat" label="Nama Klasifikasi Surat" />
        </div>

        <x-button type="submit" class="w-fit mt-2">
            @if ($route['method'] == 'POST')
                Tambah
            @else
                Perbarui
            @endif
            Klasifikasi Surat
        </x-button>
    </form>
@endsection
