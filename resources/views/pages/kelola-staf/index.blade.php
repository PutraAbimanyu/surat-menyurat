@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <form action="{{ route('kelola-staf.index') }}" method="get">
        <div class="flex w-full justify-between flex-wrap gap-4 mb-4">
            <x-button href="{{ route('kelola-staf.create') }}">
                <i class='bxr bx-plus -ml-1'></i>
                Tambah Staf
            </x-button>
            <div class="flex gap-2 flex-wrap">
                <x-text-field value="{{ request('cari') }}" name="cari" placeholder="Cari staf" />
                <x-button type="submit">
                    Cari
                </x-button>
                @if (request('cari'))
                    <x-button color="red" class="w-fit" href="{{ route('kelola-staf.index') }}">Hapus
                        pencarian</x-button>
                @endif
            </div>
        </div>

        @if (session('berhasil'))
            <x-alert class="mb-3" type="success">{{ session('berhasil') }}</x-alert>
        @endif

        <x-dropdown label="Status Akun" name="akun-nonaktif" onchange="this.form.submit()" class="mb-4 w-fit">
            <x-dropdown.option value="false" :selected="request('akun-nonaktif') == 'false'">
                Aktif
            </x-dropdown.option>
            <x-dropdown.option value="true" :selected="request('akun-nonaktif') == 'true'">
                Nonaktif
            </x-dropdown.option>
        </x-dropdown>

    </form>

    <x-table>
        <x-table.thead>
            <x-table.th>No.</x-table.th>
            <x-table.th>Nama</x-table.th>
            <x-table.th>Username</x-table.th>
            <x-table.th>Tanggal Dibuat</x-table.th>
            <x-table.th>Aksi</x-table.th>
        </x-table.thead>
        @if (count($daftarUserStaf) > 0)
            @foreach ($daftarUserStaf as $userStaf)
                <x-table.tbody>
                    <x-table.td>{{ ($daftarUserStaf->currentPage() - 1) * $daftarUserStaf->perPage() + $loop->iteration }}.</x-table.td>
                    <x-table.td>{{ $userStaf->nama }}</x-table.td>
                    <x-table.td>{{ $userStaf->username }}</x-table.td>
                    <x-table.td>{{ Carbon::parse($userStaf->created_at)->translatedFormat('d F Y, H:i:s') }}</x-table.td>
                    <x-table.td>
                        <div class="flex gap-2 flex-wrap">
                            <x-button href="{{ route('kelola-staf.show', $userStaf->id) }}">Detail</x-button>
                            <x-button href="{{ route('kelola-staf.edit', $userStaf->id) }}" color="yellow">Edit</x-button>
                            @if ($userStaf->akun_nonaktif)
                                <form
                                    onsubmit="return confirm('Apakah anda yakin ingin mengaktifkan staf {{ $userStaf->nama }}?')"
                                    action="{{ route('kelola-staf.destroy', $userStaf->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <x-button color="green">
                                        Aktifkan
                                    </x-button>
                                </form>
                            @else
                                <form
                                    onsubmit="return confirm('Apakah anda yakin ingin menonaktifkan staf {{ $userStaf->nama }}?')"
                                    action="{{ route('kelola-staf.destroy', $userStaf->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <x-button color="red">
                                        Nonaktifkan
                                    </x-button>
                                </form>
                            @endif

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

    {{ $daftarUserStaf->links() }}
@endsection
