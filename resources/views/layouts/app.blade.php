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
        display: none !important;
    }
</style>

<body x-data="{ isOpenSidebarMenu: false }" class="font-inter bg-slate-100">
    @include('layouts.header')
    @include('layouts.sidebar')

    <div class="pl-64 pt-24 p-8 max-md:p-0">
        <div class="md:hidden pt-20">
            <h1 class="font-bold text-3xl max-md:pl-4 max-md:pt-4">{{ $title }}</h1>
        </div>

        <main class="max-md:mt-6 bg-white p-4 md:rounded-lg">
            @yield('content')
        </main>
    </div>

    @stack('script')

</body>

</html>
