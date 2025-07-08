@extends('layouts.app')

@php
    use Carbon\Carbon;
    use App\Models\Peran;
@endphp

@section('content')
    <form action="{{ route('surat-disposisi.index') }}" method="get">
        <div class="flex w-full justify-between flex-wrap gap-4 mb-4">
            @if (Auth::user()->peran_id === Peran::PERAN_ADMIN_ID || Auth::user()->peran_id === Peran::PERAN_STAF_ID)
                <x-button href="{{ route('surat-disposisi.create') }}">
                    <i class='bxr bx-plus -ml-1'></i>
                    Tambah Surat Disposisi
                </x-button>
            @endif
            <div class="flex gap-3 flex-wrap">
                <x-text-field value="{{ request('cari') }}" name="cari" placeholder="Cari surat disposisi" />
                <x-button type="submit">
                    Cari
                </x-button>
                @if (request('cari'))
                    <x-button color="red" class="w-fit" href="{{ route('surat-disposisi.index') }}">Hapus
                        pencarian</x-button>
                @endif
            </div>
        </div>

        @if (session('berhasil'))
            <x-alert class="mb-3" type="success">{{ session('berhasil') }}</x-alert>
        @endif

        <x-dropdown label="Pilih Klasifikasi" name="klasifikasi-surat-id" onchange="this.form.submit()" class="mb-4 w-fit">
            <x-dropdown.option value="semua" :selected="request('klasifikasi-surat-id') == 'semua'">
                Semua
            </x-dropdown.option>
            @foreach ($daftarKlasifikasiSurat as $klasifikasiSurat)
                <x-dropdown.option value="{{ $klasifikasiSurat->id }}" :selected="request('klasifikasi-surat-id') == $klasifikasiSurat->id">
                    {{ $klasifikasiSurat->nama_klasifikasi }}
                </x-dropdown.option>
            @endforeach
        </x-dropdown>

    </form>

    <x-table>
        <x-table.thead>
            <x-table.th>No.</x-table.th>
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
        @if (count($daftarSuratDisposisi) > 0)
            @foreach ($daftarSuratDisposisi as $suratDisposisi)
                <x-table.tbody>
                    <x-table.td>{{ ($daftarSuratDisposisi->currentPage() - 1) * $daftarSuratDisposisi->perPage() + $loop->iteration }}.</x-table.td>
                    <x-table.td>{{ $suratDisposisi->klasifikasiSurat->nama_klasifikasi }}</x-table.td>
                    <x-table.td>{{ $suratDisposisi->nomor_surat }}</x-table.td>
                    <x-table.td>{{ $suratDisposisi->pengirim }}</x-table.td>
                    <x-table.td>{{ $suratDisposisi->nomor_agenda }}</x-table.td>
                    <x-table.td>
                        <a href="{{ '/storage/' . $suratDisposisi->lampiran }}" class="text-blue-500 hover:underline"
                            target="_blank" title="{{ $suratDisposisi->lampiran ?? 'Tidak ada lampiran' }}">
                            {{ Str::limit($suratDisposisi->lampiran, 20) }}
                        </a>
                    </x-table.td>
                    <x-table.td>{{ $suratDisposisi->user->nama }}</x-table.td>
                    <x-table.td>{{ Carbon::parse($suratDisposisi->tanggal_surat)->translatedFormat('d F Y') }}</x-table.td>
                    <x-table.td>{{ Carbon::parse($suratDisposisi->tanggal_diterima)->translatedFormat('d F Y') }}</x-table.td>
                    <x-table.td>
                        @if ($suratDisposisi->diverifikasi == null)
                        <span class="text-black">Menunggu</span>
                        @elseif ($suratDisposisi->diverifikasi)
                            <span class="text-green-500">Disetujui</span>
                        @else
                            <span class="text-red-500">Ditolak</span>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        <div class="flex gap-2 flex-wrap">
                            <x-button href="{{ route('surat-disposisi.show', $suratDisposisi->id) }}">Detail</x-button>
                            @if (Auth::user()->peran_id !== Peran::PERAN_KADES_ID)
                                <x-button href="{{ route('surat-disposisi.edit', $suratDisposisi->id) }}"
                                    color="yellow">Edit</x-button>
                                <form
                                    onsubmit="return confirm('Apakah anda yakin ingin menghapus surat disposisi {{ $suratDisposisi->nomor_surat }}?')"
                                    action="{{ route('surat-disposisi.destroy', $suratDisposisi->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <x-button color="red">
                                        Hapus
                                    </x-button>
                                </form>
                            @endif
                        </div>
                    </x-table.td>
                </x-table.tbody>
            @endforeach
        @else
            <x-table.tbody>
                <x-table.td colspan="11" class="text-center">Data kosong!</x-table.td>
            </x-table.tbody>
        @endif
    </x-table>

    {{ $daftarSuratDisposisi->links() }}
@endsection
