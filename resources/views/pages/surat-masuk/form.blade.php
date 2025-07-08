@extends('layouts.app')

@section('content')
    <x-button.back href="{{ route('surat-masuk.index') }}" />
    <form action="{{ $route['action'] }}" method="POST" enctype="multipart/form-data"
        class="flex bg-white rounded-lg flex-col gap-2 mt-4">
        @csrf
        @method($route['method'])

        <div class="space-y-3">
            <section class="grid lg:grid-cols-3 gap-3 items-center">
                <x-text-field name="nomor_surat" value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}"
                    placeholder="Masukkan nomor surat masuk" label="Nomor Surat Masuk" />
                <x-text-field name="pengirim" value="{{ old('pengirim', $suratMasuk->pengirim) }}"
                    placeholder="Masukkan pengirim" label="Pengirim" />
                <x-text-field name="nomor_agenda" value="{{ old('nomor_agenda', $suratMasuk->nomor_agenda) }}"
                    placeholder="Masukkan nomor agenda" label="Nomor Agenda" />
            </section>
            <section class="grid lg:grid-cols-2 gap-3 items-center">
                <x-date-input name="tanggal_surat" value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat) }}"
                    placeholder="Masukkan tanggal surat" label="Tanggal Surat" />
                <x-date-input name="tanggal_diterima" value="{{ old('tanggal_diterima', $suratMasuk->tanggal_diterima) }}"
                    placeholder="Masukkan tanggal diterima" label="Tanggal Diterima" />
            </section>
            <x-text-field.area name="keterangan" value="{{ old('keterangan', $suratMasuk->keterangan) }}"
                placeholder="Masukkan keterangan" label="Keterangan" />
            <section class="grid lg:grid-cols-2 gap-3 items-center">
                <x-dropdown name="klasifikasi_surat_id" label="Pilih klasifikasi surat" :placeholder=true>
                    @foreach ($daftarKlasifikasiSurat as $klasifikasiSurat)
                        <x-dropdown.option value="{{ $klasifikasiSurat->id }}" :selected="$suratMasuk->klasifikasi_surat_id == $klasifikasiSurat->id">
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
