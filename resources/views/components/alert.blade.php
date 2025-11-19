@props(['type' => 'success'])

@php
    $classes = [
        'success' => 'bg-green-100 border-green-200 text-green-700',
        'error' => 'bg-red-100 border-red-200 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-200 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-200 text-blue-700',
    ];
    
    $icons = [
        'success' => 'check-circle',
        'error' => 'exclamation-circle',
        'warning' => 'exclamation-triangle',
        'info' => 'info-circle',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg border px-4 py-3 mb-6 flex items-center gap-3 ' . $classes[$type]]) }}>
    <i class="fas fa-{{ $icons[$type] }} text-lg"></i>
    <div class="flex-1">
        {{ $slot }}
    </div>
    <button onclick="this.parentElement.remove()" class="hover:opacity-75">
        <i class="fas fa-times"></i>
    </button>
</div>
