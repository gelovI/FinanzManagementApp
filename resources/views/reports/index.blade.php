<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Berichte') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Tabs für Einnahmen und Ausgaben -->
        <div x-data="{ activeTab: 'income', editingId: null }">
            <div class="flex space-x-4 mb-6">
                <button
                    @click="activeTab = 'income'; editingId = null"
                    :class="activeTab === 'income' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded">
                    Einnahmen
                </button>
                <button
                    @click="activeTab = 'expense'; editingId = null"
                    :class="activeTab === 'expense' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded">
                    Ausgaben
                </button>
            </div>

            <!-- Einnahmen Tab -->
            <div x-show="activeTab === 'income'" class="bg-white shadow-sm rounded-lg p-4">
                <table class="min-w-full bg-white rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Betrag (€)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beschreibung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomes as $income)
                        <tr x-show="editingId !== {{ $income->id }}" class="bg-white border-b">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($income->date)->format('d.m.Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $income->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">€ {{ number_format($income->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $income->description }}</td>
                            <td class="px-6 py-4 text-sm flex space-x-2">
                                <!-- Bearbeiten Button -->
                                <button @click="editingId = {{ $income->id }}" class="text-blue-600 hover:underline">
                                    <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        class="h-4 w-4" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                                        <path fill="#231F20" d="M62.828,12.482L51.514,1.168c-1.562-1.562-4.093-1.562-5.657,0.001c0,0-44.646,44.646-45.255,45.255
                                C-0.006,47.031,0,47.996,0,47.996l0.001,13.999c0,1.105,0.896,2,1.999,2.001h4.99c0.003,0,9.01,0,9.01,0s0.963,0.008,1.572-0.602
                                s45.256-45.257,45.256-45.257C64.392,16.575,64.392,14.046,62.828,12.482z M37.356,12.497l3.535,3.536L6.95,49.976l-3.536-3.536
                                L37.356,12.497z M8.364,51.39l33.941-33.942l4.243,4.243L12.606,55.632L8.364,51.39z M3.001,61.995c-0.553,0-1.001-0.446-1-0.999
                                v-1.583l2.582,2.582H3.001z M7.411,61.996l-5.41-5.41l0.001-8.73l14.141,14.141H7.411z M17.557,60.582l-3.536-3.536l33.942-33.94
                                l3.535,3.535L17.557,60.582z M52.912,25.227L38.771,11.083l2.828-2.828l14.143,14.143L52.912,25.227z M61.414,16.725l-4.259,4.259
                                L43.013,6.841l4.258-4.257c0.782-0.782,2.049-0.782,2.829-0.002l11.314,11.314C62.195,14.678,62.194,15.943,61.414,16.725z" />
                                    </svg>
                                </button>
                                <!-- Löschen Button -->
                                <form action="{{ route('incomes.destroy', $income->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        <svg class="h-5 w-5 mt-[5px]" viewBox="0 0 1024 1024" fill="red" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M32 241.6c-11.2 0-20-8.8-20-20s8.8-20 20-20l940 1.6c11.2 0 20 8.8 20 20s-8.8 20-20 20L32 241.6zM186.4 282.4c0-11.2 8.8-20 20-20s20 8.8 20 20v688.8l585.6-6.4V289.6c0-11.2 8.8-20 20-20s20 8.8 20 20v716.8l-666.4 7.2V282.4z" fill="" />
                                            <path d="M682.4 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM367.2 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM524.8 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM655.2 213.6v-48.8c0-17.6-14.4-32-32-32H418.4c-18.4 0-32 14.4-32 32.8V208h-40v-42.4c0-40 32.8-72.8 72.8-72.8H624c40 0 72.8 32.8 72.8 72.8v48.8h-41.6z" fill="" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr x-show="editingId === {{ $income->id }}" class="bg-gray-100 border-b">
                            <form action="{{ route('incomes.update', $income->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td class="px-6 py-4 text-sm">
                                    <input type="date" name="date" value="{{ $income->date }}" class="w-full border rounded px-2 py-1">
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <select name="category_id" class="w-full border rounded px-2 py-1">
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $income->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <input type="number" name="amount" value="{{ $income->amount }}" step="0.01" class="w-full border rounded px-2 py-1">
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <input type="text" name="description" value="{{ $income->description }}" class="w-full border rounded px-2 py-1">
                                </td>
                                <td class="px-6 py-4 text-sm flex space-x-2">
                                    <button type="submit" class="bg-green-300 hover:bg-green-500 rounded text-base text-white p-2">Speichern</button>
                                    <button type="button" @click="editingId = null" class="bg-blue-300 hover:bg-blue-500 rounded text-base text-white p-2">Abbrechen</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Ausgaben Tab -->
            <div x-show="activeTab === 'expense'" class="bg-white shadow-sm rounded-lg p-4">
                <table class="min-w-full bg-white rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Betrag (€)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beschreibung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                        <tr x-show="editingId !== {{ $expense->id }}" class="bg-white border-b">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($expense->date)->format('d.m.Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $expense->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">€ {{ number_format($expense->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $expense->description }}</td>
                            <td class="px-6 py-4 text-sm flex space-x-2">
                                <!-- Bearbeiten Button -->
                                <button @click="editingId = {{ $expense->id }}" class="text-blue-600 hover:underline">
                                    <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        class="h-4 w-4" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                                        <path fill="#231F20" d="M62.828,12.482L51.514,1.168c-1.562-1.562-4.093-1.562-5.657,0.001c0,0-44.646,44.646-45.255,45.255
                                C-0.006,47.031,0,47.996,0,47.996l0.001,13.999c0,1.105,0.896,2,1.999,2.001h4.99c0.003,0,9.01,0,9.01,0s0.963,0.008,1.572-0.602
                                s45.256-45.257,45.256-45.257C64.392,16.575,64.392,14.046,62.828,12.482z M37.356,12.497l3.535,3.536L6.95,49.976l-3.536-3.536
                                L37.356,12.497z M8.364,51.39l33.941-33.942l4.243,4.243L12.606,55.632L8.364,51.39z M3.001,61.995c-0.553,0-1.001-0.446-1-0.999
                                v-1.583l2.582,2.582H3.001z M7.411,61.996l-5.41-5.41l0.001-8.73l14.141,14.141H7.411z M17.557,60.582l-3.536-3.536l33.942-33.94
                                l3.535,3.535L17.557,60.582z M52.912,25.227L38.771,11.083l2.828-2.828l14.143,14.143L52.912,25.227z M61.414,16.725l-4.259,4.259
                                L43.013,6.841l4.258-4.257c0.782-0.782,2.049-0.782,2.829-0.002l11.314,11.314C62.195,14.678,62.194,15.943,61.414,16.725z" />
                                    </svg>
                                </button>
                                <!-- Löschen Button -->
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        <svg class="h-5 w-5 mt-[5px]" viewBox="0 0 1024 1024" fill="red" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M32 241.6c-11.2 0-20-8.8-20-20s8.8-20 20-20l940 1.6c11.2 0 20 8.8 20 20s-8.8 20-20 20L32 241.6zM186.4 282.4c0-11.2 8.8-20 20-20s20 8.8 20 20v688.8l585.6-6.4V289.6c0-11.2 8.8-20 20-20s20 8.8 20 20v716.8l-666.4 7.2V282.4z" fill="" />
                                            <path d="M682.4 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM367.2 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM524.8 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM655.2 213.6v-48.8c0-17.6-14.4-32-32-32H418.4c-18.4 0-32 14.4-32 32.8V208h-40v-42.4c0-40 32.8-72.8 72.8-72.8H624c40 0 72.8 32.8 72.8 72.8v48.8h-41.6z" fill="" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr x-show="editingId === {{ $expense->id }}" class="bg-gray-100 border-b">
                            <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td class="px-6 py-4 text-sm">
                                    <input type="date" name="date" value="{{ $expense->date }}" class="w-full border rounded px-2 py-1">
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <select name="category_id" class="w-full border rounded px-2 py-1">
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <input type="number" name="amount" value="{{ $expense->amount }}" step="0.01" class="w-full border rounded px-2 py-1">
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <input type="text" name="description" value="{{ $expense->description }}" class="w-full border rounded px-2 py-1">
                                </td>
                                <td class="px-6 py-4 text-sm flex space-x-2">
                                    <button type="submit" class="text-green-600 hover:underline">Speichern</button>
                                    <button type="button" @click="editingId = null" class="text-gray-600 hover:underline">Abbrechen</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>