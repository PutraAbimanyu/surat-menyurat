@php
    use App\Models\Peran;

    $classMenu = ' w-full flex items-center p-3 gap-3 relative hover:opacity-100 text-xl';
    $classSubMenu = ' w-full flex items-center py-3 ml-11 gap-3 relative hover:opacity-100 font-semibold';
@endphp

<aside class="fixed top-0 bg-slate-900 text-white w-60 h-screen pb-4 duration-300 cursor-pointer z-20"
    x-bind:class="{
        'left-0': isOpenSidebarMenu,
        'max-md:-left-64': !isOpenSidebarMenu
    }">
    <div class="flex h-24 items-center max-md:justify-between md:justify-center px-5">
        <img class="h-16" src="{{ asset('images/logo/kabupaten-banjar.png') }}">
        <div class="md:hidden">
            <i @click="isOpenSidebarMenu = false" class="bxr bx-x text-white text-4xl"></i>
        </div>
    </div>
    <nav class="w-full flex flex-col mt-4 gap-1">
        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
            class="
        {{ Request::is('dashboard*') ? 'bg-primary/10' : 'opacity-70' }}
       {{ $classMenu }}">
            <i class='bxr bxs-home-alt-2'></i>
            <span class="font-semibold text-lg">Dashboard</span>
            @if (Request::is('dashboard*'))
                <div class="w-1 absolute h-full bg-primary right-0"></div>
            @endif
        </a>
        {{-- TRANSAKSI SURAT --}}
        <div x-data='@json(['isOpenSubMenu' => Request::is('transaksi-surat*')])'>
            @if (Request::is('transaksi-surat*'))
                <div class="w-1 absolute h-[3.215rem] bg-primary right-0"></div>
            @endif
            <button @click="isOpenSubMenu = !isOpenSubMenu"
                class="{{ Request::is('transaksi-surat*') ? 'bg-primary/10' : 'opacity-70' }}
       {{ $classMenu }}">
                <i class='bxr bxs-envelope-alt'></i>
                <span class="font-semibold text-lg">Transaksi Surat</span>
                @if (Request::is('surat-masuk*'))
                    <div class="w-1 absolute h-full bg-primary right-0"></div>
                @endif
                <i :class="{ 'rotate-180': isOpenSubMenu }"
                    class='bxr bx-chevron-down duration-150 text-xl ml-auto'></i>
            </button>
            <div x-cloak x-show="isOpenSubMenu">
                <a href="{{ route('surat-masuk.index') }}"
                    class="{{ Request::is('transaksi-surat/surat-masuk*') ? false : 'opacity-70' }}
       {{ $classSubMenu }}">
                    <span>Surat Masuk</span>
                </a>
                <a href="{{ route('surat-keluar.index') }}"
                    class="{{ Request::is('transaksi-surat/surat-keluar*') ? false : 'opacity-70' }}
       {{ $classSubMenu }}">
                    <span>Surat Keluar</span>
                </a>
                <a href="{{ route('surat-disposisi.index') }}"
                    class="{{ Request::is('transaksi-surat/surat-disposisi*') ? false : 'opacity-70' }}
       {{ $classSubMenu }}">
                    <span>Surat Disposisi</span>
                </a>
            </div>
        </div>
        @if (Auth::user()->peran_id == Peran::PERAN_ADMIN_ID)
            {{-- KLASIFIKASI SURAT --}}
            <a href="{{ route('klasifikasi-surat.index') }}"
                class="{{ Request::is('klasifikasi-surat*') ? 'bg-primary/10' : 'opacity-70' }}
       {{ $classMenu }}">
                <i class='bxr bxs-refresh-cw-dot'></i>
                <span class="font-semibold text-lg">Klasifikasi Surat</span>
                @if (Request::is('klasifikasi-surat*'))
                    <div class="w-1 absolute h-full bg-primary right-0"></div>
                @endif
            </a>
            {{-- TAMBAH STAF --}}
            {{-- <a href="{{ route('kelola-staf.index') }}"
                class="{{ Request::is('kelola-staf*') ? 'bg-primary/10' : 'opacity-70' }}
       {{ $classMenu }}">
                <i class='bxr bxs-user-plus'></i>
                <span class="font-semibold text-lg">Kelola Staf</span>
                @if (Request::is('kelola-staf*'))
                    <div class="w-1 absolute h-full bg-primary right-0"></div>
                @endif
            </a> --}}
            {{-- TAMBAH ADMIN --}}
            <a href="{{ route('kelola-admin.index') }}"
                class="{{ Request::is('kelola-admin*') ? 'bg-primary/10' : 'opacity-70' }}
       {{ $classMenu }}">
                <i class='bxr bxs-user-plus'></i>
                <span class="font-semibold text-lg">Kelola Admin</span>
                @if (Request::is('kelola-admin*'))
                    <div class="w-1 absolute h-full bg-primary right-0"></div>
                @endif
            </a>
        @endif
        @if (Auth::user()->peran_id == Peran::PERAN_KADES_ID)
            <a href="{{ route('verifikasi-surat.index') }}"
                class="{{ Request::is('verifikasi-surat*') ? 'bg-primary/10' : 'opacity-70' }}
       {{ $classMenu }}">
                <i class='bx bx-check'></i>
                <span class="font-semibold text-lg">Verifikasi Surat</span>
                @if (Request::is('verifikasi-surat*'))
                    <div class="w-1 absolute h-full bg-primary right-0"></div>
                @endif
            </a>
        @endif
        {{-- PROFIL --}}
        <a href="{{ route('profil.index') }}"
            class="{{ Request::is('profil*') ? 'bg-primary/10' : 'opacity-70' }}
       {{ $classMenu }}">
            <i class='bxr bxs-user'></i>
            <span class="font-semibold text-lg">Profil</span>
            @if (Request::is('profil*'))
                <div class="w-1 absolute h-full bg-primary right-0"></div>
            @endif
        </a>
    </nav>
</aside>
