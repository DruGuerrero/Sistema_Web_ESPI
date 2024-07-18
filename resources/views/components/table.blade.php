<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-3 min-w-full inline-block align-middle py-4">
            <div class="border rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($headers as $header)
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($rows as $index => $row)
                            <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                @foreach ($row as $cell)
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">
                                        {!! $cell !!}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
