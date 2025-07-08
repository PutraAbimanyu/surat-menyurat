<div {{ $attributes->merge(['class' => 'flex flex-col']) }}>
    @if ($label ?? false)
        <label for="{{ $label }}" class="text-black mb-1.5 font-medium">{{ $label }}</label>
    @endif
    <input id="{{ $name }}" type="{{ $attributes->get('type') ?? 'text' }}"
        placeholder="{{ $attributes->get('placeholder') }}" name="{{ $attributes->get('name') }}"
        value="{{ $attributes->get('value') }}"
        class="w-full px-3 py-2 bg-gray-100 border outline-none rounded-lg focus:ring-4 ring-primary/40 duration-150">
    @error($attributes->get('name'))
        <p class="text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
