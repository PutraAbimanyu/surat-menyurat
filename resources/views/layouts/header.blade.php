<header x-data="{ isOpenProfileMenu: false }"
    class="fixed bg-white w-full h-20 flex items-center justify-between px-8 max-md:px-4 md:pl-64 z-10">
    <h1 class="text-3xl font-bold max-md:hidden">{{ $title }}</h1>
    <div class="flex md:hidden">
        <i @click="isOpenSidebarMenu = true" class='bxr bx-menu-left text-5xl cursor-pointer'></i>
    </div>
    <div class="flex items-center gap-2 cursor-pointer relative" @click="isOpenProfileMenu = !isOpenProfileMenu">
        <i class='bxr bx-chevron-down text-xl'></i>
        <h1>{{ Auth::user()->nama }}</h1>
    </div>

    <div x-cloak x-show="isOpenProfileMenu" x-transition:enter="transition ease-out duration-100 transform"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75 transform" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white absolute top-24 p-3 rounded-lg shadow right-8 max-md:right-4 w-44">
        <h1>{{ Auth::user()->nama }}</h1>
        <div class="w-full h-0.5 bg-black/10 my-2"></div>
        <p class="text-gray-500">Username:</p>
        <h1 class="mb-2 font-semibold">{{ Auth::user()->username }}</h1>
        <form action="{{ route('logout') }}" method="post" class="mt-3 flex">
            @csrf
            <x-button color="red" type="submit" size="small" class="w-full !justify-start">
                <i class='bxr bxs-arrow-in-left-square-half text-white text-2xl -ml-1'></i>
                Logout
            </x-button>
        </form>
    </div>
</header>
