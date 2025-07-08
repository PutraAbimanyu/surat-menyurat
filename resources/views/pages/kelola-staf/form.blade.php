@extends('layouts.app')

@section('content')
    <x-button.back href="{{ route('kelola-staf.index') }}" />
    <form action="{{ $route['action'] }}" method="POST" class="flex bg-white rounded-lg flex-col gap-2 mt-4">
        @csrf
        @method($route['method'])

        <div class="space-y-3">
            <section class="grid lg:grid-cols-2 gap-3 items-center">
                <x-text-field name="nama" value="{{ old('nama', $userStaf->nama) }}" placeholder="Masukkan nama staf"
                    label="Nama Staf" />
                <x-text-field name="username" value="{{ old('username', $userStaf->username) }}"
                    placeholder="Masukkan username staf" label="Username Staf" />
                <x-text-field name="password" placeholder="Masukkan password" label="Password" type="password" />
                <x-text-field name="password_confirmation" placeholder="Masukkan konfirmasi password"
                    label="Konfirmasi Password" type="password" />
            </section>

        </div>

        <x-button type="submit" class="w-fit mt-2">
            @if ($route['method'] == 'POST')
                Tambah
            @else
                Perbarui
            @endif
            Staf
        </x-button>
    </form>
@endsection
