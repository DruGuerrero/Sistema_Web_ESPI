<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400 px-1']) }}>
    {{ $slot }}
</button>
