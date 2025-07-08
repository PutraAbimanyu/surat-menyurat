@extends('layouts.app')

@section('content')
    <x-button.back href="{{ route('surat-keluar.index') }}" />
    <form action="{{ $route['action'] }}" method="POST" enctype="multipart/form-data"
        class="flex bg-white rounded-lg flex-col gap-2 mt-4">
        @csrf
        @method($route['method'])

        <div class="space-y-3">
            <section class="grid lg:grid-cols-3 gap-3 items-center">
                <x-text-field name="nomor_surat" value="{{ old('nomor_surat', $suratKeluar->nomor_surat) }}"
                    placeholder="Keluarkan nomor surat keluar" label="Nomor Surat Keluar" />
                <x-text-field name="pengirim" value="{{ old('pengirim', $suratKeluar->pengirim) }}"
                    placeholder="Keluarkan pengirim" label="Pengirim" />
                <x-text-field name="nomor_agenda" value="{{ old('nomor_agenda', $suratKeluar->nomor_agenda) }}"
                    placeholder="Keluarkan nomor agenda" label="Nomor Agenda" />
            </section>
            <section class="grid lg:grid-cols-2 gap-3 items-center">
                <x-date-input name="tanggal_surat" value="{{ old('tanggal_surat', $suratKeluar->tanggal_surat) }}"
                    placeholder="Keluarkan tanggal surat" label="Tanggal Surat" />
                <x-date-input name="tanggal_diterima" value="{{ old('tanggal_diterima', $suratKeluar->tanggal_diterima) }}"
                    placeholder="Keluarkan tanggal diterima" label="Tanggal Diterima" />
            </section>
            <x-text-field.area name="keterangan" value="{{ old('keterangan', $suratKeluar->keterangan) }}"
                placeholder="Keluarkan keterangan" label="Keterangan" />
            <section class="grid lg:grid-cols-2 gap-3 items-center">
                <x-dropdown name="klasifikasi_surat_id" label="Pilih klasifikasi surat" :placeholder=true>
                    @foreach ($daftarKlasifikasiSurat as $klasifikasiSurat)
                        <x-dropdown.option value="{{ $klasifikasiSurat->id }}" :selected="$suratKeluar->klasifikasi_surat_id == $klasifikasiSurat->id">
                            {{ $klasifikasiSurat->nama_klasifikasi }}
                        </x-dropdown.option>
                    @endforeach
                </x-dropdown>
                <x-file-input name="lampiran" label="Lampiran Surat" />
            </section>

        </div>

        <x-button type="submit" class="w-fit mt-2">
            @if ($route['method'] == 'POST')
                Tambah
            @else
                Perbarui
            @endif
            Surat
        </x-button>
    </form>
@endsection
