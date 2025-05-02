@props([
    'href',
    'method' => 'get',
    'active' => false,
])

@php
    $method = strtoupper($method);
    $isPost = $method === 'POST';
@endphp

@if($isPost)
    <form action="{{ $href }}" method="POST" class="inline">
        @csrf
        @method($method)
        <button type="submit" {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out text-start' . ($active ? ' font-semibold' : '')]) }}>
            {{ $slot }}
        </button>
    </form>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out text-start' . ($active ? ' font-semibold' : '')]) }}>
        {{ $slot }}
    </a>
@endif
