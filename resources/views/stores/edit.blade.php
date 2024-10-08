<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Store') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form method="POST" action="{{ route('stores.update', ['store' => $store]) }}" class="p-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $store->name" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="type" :value="__('Store type')" />
                        <select id="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" value="{{ old('type') }}" name="type" required>
                            <option value="">Please Select</option>
                            <option value="1" @if(old('type') == 1 || $store->type->value == 1) selected="selected" @endif>Takeaway</option>
                            <option value="2" @if(old('type') == 2 || $store->type->value == 2) selected="selected" @endif>Shop</option>
                            <option value="3" @if(old('type') == 3 || $store->type->value == 3) selected="selected" @endif>Restaurant</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="max_delivery_distance" :value="__('Max delivery distance')" />
                        <x-text-input id="max_delivery_distance" class="block mt-1 w-full" type="number" name="max_delivery_distance" :value="old('max_delivery_distance') ?? $store->max_delivery_distance" required autocomplete="max_delivery_distance" />
                        <x-input-error :messages="$errors->get('max_delivery_distance')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="latitude" :value="__('Latitude')" />
                        <x-text-input id="latitude" class="block mt-1 w-full" inputmode="decimal" step="0.01" type="number" name="latitude" :value="old('latitude') ?? $store->latitude" required autocomplete="latitude" />
                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="longitude" :value="__('Longitude')" />
                        <x-text-input id="longitude" class="block mt-1 w-full" inputmode="decimal" step="0.01" type="number" name="longitude" :value="old('longitude') ?? $store->longitude" required autocomplete="longitude" />
                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                    </div>

                    <div class="block mt-4">
                        <label for="open" class="inline-flex items-center">
                            <input id="open" type="checkbox" value="true" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="open" @if(old('open') ?? $store->open) checked="true" @endif>
                            <span class="ms-2 text-sm text-gray-600">{{ __('Open') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-3">
                            {{ __('Update Store') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
