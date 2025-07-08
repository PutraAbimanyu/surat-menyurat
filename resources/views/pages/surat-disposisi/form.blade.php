@extends('layouts.app')

@section('content')
    <x-button.back href="{{ route('surat-disposisi.index') }}" />
    <form action="{{ $route['action'] }}" method="POST" enctype="multipart/form-data"
        class="flex bg-white rounded-lg flex-col gap-2 mt-4">
        @csrf
        @method($route['method'])

        <div class="space-y-3">
            <section class="grid lg:grid-cols-3 gap-3 items-center">
                <x-text-field name="nomor_surat" value="{{ old('nomor_surat', $suratDisposisi->nomor_surat) }}"
                    placeholder="Disposisikan nomor surat disposisi" label="Nomor Surat Disposisi" />
                <x-text-field name="pengirim" value="{{ old('pengirim', $suratDisposisi->pengirim) }}"
                    placeholder="Disposisikan pengirim" label="Pengirim" />
                <x-text-field name="nomor_agenda" value="{{ old('nomor_agenda', $suratDisposisi->nomor_agenda) }}"
                    placeholder="Disposisikan nomor agenda" label="Nomor Agenda" />
            </section>
            <section class="grid lg:grid-cols-2 gap-3 items-center">
                <x-date-input name="tanggal_surat" value="{{ old('tanggal_surat', $suratDisposisi->tanggal_surat) }}"
                    placeholder="Disposisikan tanggal surat" label="Tanggal Surat" />
                <x-date-input name="tanggal_diterima"
                    value="{{ old('tanggal_diterima', $suratDisposisi->tanggal_diterima) }}"
                    placeholder="Disposisikan tanggal diterima" label="Tanggal Diterima" />
            </section>
            <x-text-field.area name="keterangan" value="{{ old('keterangan', $suratDisposisi->keterangan) }}"
                placeholder="Disposisikan keterangan" label="Keterangan" />
            <section class="grid lg:grid-cols-2 gap-3 items-center">
                <x-dropdown name="klasifikasi_surat_id" label="Pilih klasifikasi surat" :placeholder=true>
                    @foreach ($daftarKlasifikasiSurat as $klasifikasiSurat)
                        <x-dropdown.option value="{{ $klasifikasiSurat->id }}" :selected="$suratDisposisi->klasifikasi_surat_id == $klasifikasiSurat->id">
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
