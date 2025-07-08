<div {{ $attributes->merge(['class' => 'flex flex-col']) }}>
    <label class="text-lg font-medium mb-2" for="{{ $attributes->get('name') }}">{{ $label }}</label>
    <input value="{{ $attributes->get('value') }}" name="{{ $attributes->get('name') }}"
        class="bg-gray-100 px-3 py-2 rounded border" id="{{ $attributes->get('name') }}" type="date">
    @error($attributes->get('name'))
        <p class="text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>
