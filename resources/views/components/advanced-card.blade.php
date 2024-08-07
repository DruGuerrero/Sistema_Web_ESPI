<div class="flex flex-col bg-white border shadow-sm rounded-xl p-4 md:p-5 min-w-[300px]">
    <h3 class="text-lg font-bold text-gray-800 text-center">
        {{ $title }}
    </h3>

    @if (!empty($image))
    <div class="flex justify-center my-3">
        <img src="{{ $image }}" alt="Course Image" class="w-50 h-auto rounded-lg object-cover">
    </div>
    @endif

    <p class="mt-2 text-gray-500 items-center justify-center text-center">
        {{ $content }}
    </p>

    <div class="mt-2 text-gray-500 space-y-2">
        @foreach ($contentBlocks as $block)
            @if(is_array($block))
                <div class="flex items-center justify-center">
                    <div class="flex items-center justify-center bg-blue-100 border border-blue-500 text-black rounded-lg py-1 px-2 w-auto h-auto">
                        {{ $block['name'] }}
                    </div>
                    <span class="px-2">{{ $block['professor'] }}</span>
                </div>
            @endif
        @endforeach
    </div>

    <div class="flex justify-between mt-4">
        <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none" href="#" data-item-id="{{ $leftButtonLink }}" onclick="handleDelete(event, '{{ $leftButtonLink }}', '{{ $title }}')">
            {{ $leftButtonText }}
            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                <path d="M10 11v6"></path>
                <path d="M14 11v6"></path>
                <rect x="6" y="2" width="12" height="4" rx="1" ry="1"></rect>
            </svg>
        </a>
        <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none" href="{{ $rightButtonLink }}">
            {{ $rightButtonText }}
            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
        </a>
    </div>
</div>
