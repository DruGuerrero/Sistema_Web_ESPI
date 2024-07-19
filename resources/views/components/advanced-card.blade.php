<!-- resources/views/components/advanced-card.blade.php -->

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
                <div class="bg-blue-100 border border-blue-500 text-black rounded-lg py-1 px-2 mr-2 w-48 text-center">
                    {{ $block['name'] }}
                </div>
                <span>{{ $block['professor'] }}</span>
            </div>
        @endforeach
    </div>
    <div class="flex justify-between mt-4">
        <a class="inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none" href="{{ $leftButtonLink }}">
            Eliminar
        </a>
        <a class="inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none" href="{{ $rightButtonLink }}">
            Ver
        </a>
    </div>
</div>