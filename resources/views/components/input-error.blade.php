@props(['messages' => null, 'for' => null])

@php
    if ($for && empty($messages)) {
        $messages = $errors->get($for);
    }
@endphp

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-danger-ink space-y-1 mt-1.5']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5 text-xs text-danger-ink font-medium">
                <svg class="w-4 h-4 text-danger-ink flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
