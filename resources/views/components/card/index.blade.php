<div class="bg-primary text-white p-4 rounded-lg hover:shadow-md duration-150 cursor-pointer flex items-center gap-4">
    @if ($icon ?? false)
        <div class="bg-black/20 p-2.5 rounded-full flex items-center text-2xl">
            {!! $icon !!}
        </div>
    @endif
    <div>
        <h1>{{ $label }}</h1>
        <b class="text-xl">{{ $value }}</b>
    </div>
</div>
