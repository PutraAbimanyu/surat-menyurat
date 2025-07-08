<div id="{{ $attributes->get('id') }}" class="flex flex-col">
    <label class="mb-1.5" for="file_input">{{ $label }}</label>
    <input name="{{ $attributes->get('name') }}"
        class="bg-gray-50 rounded-lg border cursor-pointer file:p-2 file:rounded-lg file:border-none file:mr-3 file:cursor-pointer"
        id="file_input" type="file">
    @error($attributes->get('name'))
        <p class="text-red-600 mt-2">{{ $message }}</p>
    @enderror

</div>
