<div {{ $attributes->merge(['class' => 'flex flex-col gap-1']) }}>
    @if ($label ?? false)
        <label for="{{ $name }}" class="text-black">{{ $label }}</label>
    @endif
    <select onchange="{{ $attributes->get('onchange') }}" name="{{ $name }}" id="{{ $name }}"
        class="bg-gray-100 border rounded-lg p-2">
        @if ($placeholder ?? false)
            <option selected disabled>{{ $label }}</option>
        @endif
        {{ $slot }}
    </select>
    @error($attributes->get('name'))
        <p class="text-red-600">{{ $message }}</p>
    @enderror
</div>
