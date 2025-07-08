{{-- 

#PANDUAN PENGGUNAAN

Silahkan gunakan properti ini pada elemen
- href = '/example' ?? {{ url()->previous }}

contoh: <x-button.back href="/example">Button</x-button.back>

--}}

<button onclick="back()"
    {{ $attributes->merge(['class' => 'flex items-center gap-1 bg-gray-100 font-semibold px-4 py-2 rounded-lg focus:ring-4 ring-gray-200 duration-150']) }}>
    <i class='bxr bx-arrow-left-stroke text-2xl -ml-2'></i>
    Kembali
</button>
<script>
    function back() {
        @if (isset($href))
            window.location.href = "{{ $href }}";
        @else
            window.history.back();
        @endif
    }
</script>
