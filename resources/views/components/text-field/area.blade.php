<div class="flex flex-col">
    @if ($label ?? false)
        <label for="{{ $label }}" class="text-black font-medium text-lg mb-2">{{ $label }}</label>
    @endif
    <textarea name="{{ $name }}" id="" rows="4" placeholder="{{ $attributes->get('placeholder') }}"
        class="resize-none px-3 py-2 bg-gray-100 text-black outline-none rounded-md focus:ring-4 duration-150 border">{{ $value ?? false }}</textarea>
    @error($attributes->get('name'))
        <p class="text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>
