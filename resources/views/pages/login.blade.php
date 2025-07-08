<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/logo/kotak-kabupaten-banjar.png') }}" type="image/png">
    <title>{{ $title }} | Surat Menyurat</title>
    {{-- IMPORT TAILWIND % ALPINE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- IMPORT BOX ICONS --}}
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    @stack('head')
</head>

<style>
    [x-cloak] {
        display: none;
    }
</style>

<body class="font-inter">

    <main class="bg-blue-50 w-full min-h-screen flex flex-col items-center justify-center"
        style="
        {{-- Membuat gambar latar belakang --}}
        background: 
        linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.3)), 
        url('{{ asset('images/menara-pandang-banjarmasin.jpeg') }}');
        background-size: cover;
        background-position: center;
        ">

        {{-- Kotak login --}}
        <section class="bg-white p-6 rounded-lg border-2 border-blue-300 shadow w-full max-w-sm">
            <div class="flex gap-4 items-center mb-4">
                {{-- Gambar logo --}}
                <img class="h-20" src="{{ asset('images/logo/kabupaten-banjar.png') }}" alt="logo-kabupaten-banjar">
                <div class="uppercase font-bold text-xl text-gray-500">
                    <span>Sistem Informasi <br> Surat Menyurat</span>
                </div>
            </div>
            <h1 class="font-extrabold text-3xl text-primary">{{ $title }}</h1>
            <p class="mt-1 text-gray-600">Selamat datang, silahkan login</p>

            {{-- Tampilan notifikasi jika login gagal --}}
            @if (session('gagal'))
                <x-alert>{{ session('gagal') }}</x-alert>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="mt-4">
                {{-- Wajid ada csrf setiap form method post --}}
                @csrf

                {{-- Input username --}}
                <x-text-field value="" name="username" label="Username" placeholder="Masukkan username" />
                {{-- Input password --}}
                <x-text-field value="" class="mt-2" name="password" label="Password"
                    placeholder="Masukkan password" type="password" />
                {{-- Tombol login --}}
                <x-button class="w-full mt-6" type="submit">Login</x-button>
            </form>
        </section>

    </main>

</body>

</html>
