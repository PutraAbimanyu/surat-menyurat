@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="flex w-full justify-between flex-wrap gap-4 mb-4">
        <x-button href="{{ route('klasifikasi-surat.create') }}">
            <i class='bxr bx-plus -ml-1'></i>
            Tambah Klasifikasi
        </x-button>
        <form action="{{ route('klasifikasi-surat.index') }}" method="get" class="flex gap-2 flex-wrap">
            <x-text-field value="{{ request('cari') }}" name="cari" placeholder="Cari klasifikasi surat" />
            <x-button type="submit">
                Cari
            </x-button>
            @if (request('cari'))
                <x-button color="red" class="w-fit" href="{{ route('klasifikasi-surat.index') }}">Hapus
                    pencarian</x-button>
            @endif
        </form>
    </div>

    @if (session('berhasil'))
        <x-alert class="mb-3" type="success">{{ session('berhasil') }}</x-alert>
    @endif
    @if (session('gagal'))
        <x-alert class="mb-3" type="error">{{ session('gagal') }}</x-alert>
    @endif

    <x-table>
        <x-table.thead>
            <x-table.th>No.</x-table.th>
            <x-table.th>Nama Klasifikasi</x-table.th>
            <x-table.th>Total Relasi</x-table.th>
            <x-table.th>Aksi</x-table.th>
        </x-table.thead>
        @if (count($daftarKlasifikasiSurat) > 0)
            @foreach ($daftarKlasifikasiSurat as $klasifikasiSurat)
                <x-table.tbody>
                    <x-table.td>{{ ($daftarKlasifikasiSurat->currentPage() - 1) * $daftarKlasifikasiSurat->perPage() + $loop->iteration }}.</x-table.td>
                    <x-table.td class="font-bold">{{ $klasifikasiSurat->nama_klasifikasi }}</x-table.td>
                    <x-table.td>{{ $klasifikasiSurat->surat->count() }} Surat</x-table.td>
                    <x-table.td>
                        <div class="flex gap-2 flex-wrap">
                            <x-button href="{{ route('klasifikasi-surat.show', $klasifikasiSurat->id) }}">Detail</x-button>
                            <x-button href="{{ route('klasifikasi-surat.edit', $klasifikasiSurat->id) }}"
                                color="yellow">Edit</x-button>
                            <form
                                onsubmit="return confirm('Apakah anda yakin ingin menghapus surat masuk {{ $klasifikasiSurat->nama_klasifikasi }}?')"
                                action="{{ route('klasifikasi-surat.destroy', $klasifikasiSurat->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <x-button color="red">
                                    Hapus
                                </x-button>
                            </form>
                        </div>
                    </x-table.td>
                </x-table.tbody>
            @endforeach
        @else
            <x-table.tbody>
                <x-table.td colspan="8" class="text-center">Data kosong!</x-table.td>
            </x-table.tbody>
        @endif
    </x-table>

    {{ $daftarKlasifikasiSurat->links() }}
@endsection
