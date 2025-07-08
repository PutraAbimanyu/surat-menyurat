@extends('layouts.app')

@php
    use App\Models\Peran;
@endphp

@section('content')
    <div class="space-y-2">
        <section class="text-lg">
            <h1>Peran:</h1>
            <strong>{{ $user->peran->nama_peran }}</strong>
        </section>
        <section class="text-lg">
            <h1>Nama:</h1>
            <strong>{{ $user->nama }}</strong>
        </section>
        <section class="text-lg">
            <h1>Username:</h1>
            <strong>{{ $user->username }}</strong>
        </section>
    </div>

    @if ($user->peran_id == Peran::PERAN_ADMIN_ID)
        @if (session('berhasil'))
            <x-alert class="mb-3" type="success">{{ session('berhasil') }}</x-alert>
        @endif

        <div class="mt-4">
            <form action="{{ route('profil.perbaruiAkun') }}" method="post" class="flex flex-col gap-2">
                @csrf
                <x-text-field name="nama" placeholder="Masukkan nama baru" label="Nama baru" />
                <x-text-field name="username" placeholder="Masukkan username baru" label="Username Baru" />
                <x-text-field name="password" placeholder="Masukkan password baru" label="Password Baru" type="password" />
                <x-text-field name="password_confirmation" placeholder="Masukkan konfirmasi password baru"
                    label="Konfirmasi Password Baru" type="password" />

                <x-button type="submit" class="w-fit mt-2">
                    Perbarui Akun
                </x-button>
            </form>
        </div>
    @endif
@endsection
