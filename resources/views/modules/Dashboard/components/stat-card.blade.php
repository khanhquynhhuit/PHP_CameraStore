@props(['title' => '', 'value' => '', 'icon' => ''])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
    <div>
        <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
    </div>
    @if ($icon)
        <div class="text-gray-400">
            {!! $icon !!}
        </div>
    @endif
</div>
