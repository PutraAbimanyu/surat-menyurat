@extends('layouts.app')

@section('content')
    <x-button.back href="{{ route('kelola-admin.index') }}" />
    <form action="{{ $route['action'] }}" method="POST" class="flex bg-white rounded-lg flex-col gap-2 mt-4">
        @csrf
        @method($route['method'])

        <div class="space-y-3">
            <section class="grid lg:grid-cols-2 gap-3 items-center">
                <x-text-field name="nama" value="{{ old('nama', $userAdmin->nama) }}" placeholder="Masukkan nama admin"
                    label="Nama Admin" />
                <x-text-field name="username" value="{{ old('username', $userAdmin->username) }}"
                    placeholder="Masukkan username admin" label="Username Admin" />
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
            Admin
        </x-button>
    </form>
@endsection
