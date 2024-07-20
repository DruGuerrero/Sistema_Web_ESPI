<div class="flex flex-col bg-white border shadow-sm rounded-xl p-4 md:p-5">
    <h3 class="text-lg font-bold text-gray-800">
        {{ $title }}
    </h3>
    <p class="mt-2 text-gray-500">
        {{ $content }}
    </p>
    <div class="mt-2 text-gray-500 space-y-2">
        @foreach ($contentBlocks as $block)
            <div class="flex items-center">
                <div class="flex items-center justify-center bg-blue-100 border border-blue-500 text-black rounded-lg py-1 px-2 mr-2 w-48 h-10 text-ellipsis overflow-hidden whitespace-nowrap">
                    {{ $block['name'] }}
                </div>
                <span>{{ $block['professor'] }}</span>
            </div>
        @endforeach
    </div>
    <div class="flex justify-between mt-4">
        <a class="inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800" href="{{ $leftButtonLink }}">
            Eliminar
        </a>
        <a class="inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800" href="{{ $rightButtonLink }}">
            Ver
            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
        </a>
    </div>
</div>
