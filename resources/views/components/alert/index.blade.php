@php
    $type = $type ?? 'error';

    switch ($type) {
        case 'error':
            $type = 'bg-red-100 border-red-200 text-red-600';
            $closeButtonColor = 'fill-red-500';
            break;
        case 'success':
            $type = 'bg-green-100 border-green-200 text-green-600';
            $closeButtonColor = 'fill-green-600';
            break;
    }
@endphp

<div x-data="{ isClose: false }" x-show="!isClose"
    class="{{ $type }} {{ $class ?? '' }} mt-4 border-2 p-3 rounded flex justify-between items-center text-sm">
    <span>{{ $slot }}</span>
    <i @click="isClose = true" class='bxr bx-x text-xl cursor-pointer {{ $closeButtonColor }}'></i>
</div>
