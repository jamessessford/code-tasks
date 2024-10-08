<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <a href="{{ route('stores.create') }}" class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 px-4 py-2 rounded shadow mb-4 inline-block">Add new store</a>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Store name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Store type
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Open?
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Max delivery distance (miles)
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $store->name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $store->type->getLabel() }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $store->open ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $store->max_delivery_distance }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('stores.edit', ['store' => $store]) }}" class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 px-4 py-2 rounded shadow inline-block">Edit store</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
