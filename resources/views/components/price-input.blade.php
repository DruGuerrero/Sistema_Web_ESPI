<div class="items-center">
    <div class="relative">
      <input type="text" id="{{ $id }}" name="{{ $name }}" class="py-3 ps-12 pe-16 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $placeholder }}">
      <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
        <span class="text-gray-500">{{ $currencySymbol }}</span>
      </div>
      <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
        <span class="text-gray-500">{{ $currency }}</span>
      </div>
    </div>
</div>