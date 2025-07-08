@extends('layouts.app')
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <x-button.back />
    <div class="grid gap-3 mt-4">
        <div class="grid gap-3 lg:grid-cols-3">
            <div>
                <h1>Nama Admin:</h1>
                <strong>{{ $userAdmin->nama }}</strong>
            </div>
            <div>
                <h1>Tanggal Dibuat:</h1>
                <strong>{{ Carbon::parse($userAdmin->created_at)->translatedFormat('d F Y, H:i:s') }}</strong>
            </div>
            <div>
                <h1>Transaksi Surat:</h1>
                <strong>{{ $userAdmin->surat->count() }} Surat</strong>
            </div>
        </div>
    </div>
@endsection
