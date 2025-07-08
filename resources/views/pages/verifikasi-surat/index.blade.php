@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <form action="{{ route('verifikasi-surat.index') }}" method="get">
        <div class="flex gap-2 flex-wrap mb-4">
            <x-text-field value="{{ request('cari') }}" name="cari" placeholder="Cari verifikasi surat" />
            <x-button type="submit">
                Cari
            </x-button>
            @if (request('cari'))
                <x-button color="red" class="w-fit" href="{{ route('verifikasi-surat.index') }}">Hapus
                    pencarian</x-button>
            @endif
        </div>

        @if (session('berhasil'))
            <x-alert class="mb-3" type="success">{{ session('berhasil') }}</x-alert>
        @endif

        <x-dropdown label="Tipe Surat" name="tipe-surat" onchange="this.form.submit()" class="mb-4 w-fit">
            <x-dropdown.option value="semua" :selected="request('klasifikasi-surat') == 'semua'">
                Semua
            </x-dropdown.option>
            @foreach ($daftarTipeSurat as $tipeSurat)
                <x-dropdown.option value="{{ $tipeSurat }}" :selected="request('tipe-surat') == $tipeSurat">
                    {{ $tipeSurat }}
                </x-dropdown.option>
            @endforeach
        </x-dropdown>
    </form>

    <x-table>
        <x-table.thead>
            <x-table.th>No.</x-table.th>
            <x-table.th>Tipe Surat</x-table.th>
            <x-table.th>Klasifikasi Surat</x-table.th>
            <x-table.th>Nomor Surat</x-table.th>
            <x-table.th>Pengirim</x-table.th>
            <x-table.th>Nomor Agenda</x-table.th>
            <x-table.th>Lampiran</x-table.th>
            <x-table.th>Diunggah</x-table.th>
            <x-table.th>Tanggal Surat</x-table.th>
            <x-table.th>Tanggal Diterima</x-table.th>
            <x-table.th>Status</x-table.th>
            <x-table.th>Aksi</x-table.th>
        </x-table.thead>
        @if (count($daftarVerifikasiSurat) > 0)
            @foreach ($daftarVerifikasiSurat as $verifikasiSurat)
                <x-table.tbody>
                    <x-table.td>{{ ($daftarVerifikasiSurat->currentPage() - 1) * $daftarVerifikasiSurat->perPage() + $loop->iteration }}.</x-table.td>
                    <x-table.td>{{ $verifikasiSurat->tipe_surat }}</x-table.td>
                    <x-table.td>{{ $verifikasiSurat->klasifikasiSurat->nama_klasifikasi }}</x-table.td>
                    <x-table.td>{{ $verifikasiSurat->nomor_surat }}</x-table.td>
                    <x-table.td>{{ $verifikasiSurat->pengirim }}</x-table.td>
                    <x-table.td>{{ $verifikasiSurat->nomor_agenda }}</x-table.td>
                    <x-table.td>
                        <a href="{{ '/storage/' . $verifikasiSurat->lampiran }}" class="text-blue-500 hover:underline"
                            target="_blank" title="{{ $verifikasiSurat->lampiran ?? 'Tidak ada lampiran' }}">
                            {{ Str::limit($verifikasiSurat->lampiran, 20) }}
                        </a>
                    </x-table.td>
                    <x-table.td>{{ $verifikasiSurat->user->nama }}</x-table.td>
                    <x-table.td>{{ Carbon::parse($verifikasiSurat->tanggal_surat)->translatedFormat('d F Y') }}</x-table.td>
                    <x-table.td>{{ Carbon::parse($verifikasiSurat->tanggal_diterima)->translatedFormat('d F Y') }}</x-table.td>
                    <x-table.td>
                        @if ($verifikasiSurat->diverifikasi)
                            <span class="text-green-500">Diverifikasi</span>
                        @else
                            <span class="text-red-500">Belum diverifikasi</span>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        <div class="flex gap-2 flex-wrap">
                            <form
                                onsubmit="return confirm('Apakah anda yakin ingin verifikasi surat {{ $verifikasiSurat->nomor_surat }}?')"
                                action="{{ route('verifikasi-surat.destroy', $verifikasiSurat->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <x-button name="diverifikasi" value="true">
                                    Verifikasi
                                </x-button>
                            </form>
                            <form
                                onsubmit="return confirm('Apakah anda yakin ingin tolak surat {{ $verifikasiSurat->nomor_surat }}?')"
                                action="{{ route('verifikasi-surat.destroy', $verifikasiSurat->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <x-button name="diverifikasi" value="false" color="red">
                                    Tolak
                                </x-button>
                            </form>
                        </div>
                    </x-table.td>
                </x-table.tbody>
            @endforeach
        @else
            <x-table.tbody>
                <x-table.td colspan="12" class="text-center">Data kosong!</x-table.td>
            </x-table.tbody>
        @endif
    </x-table>

    {{ $daftarVerifikasiSurat->links() }}
@endsection
