<x-app-layout>
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sparpläne') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik -->
            <div x-data="{ showForm: false }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold">Übersicht</h3>
                    <div class="flex justify-between mt-4">
                        <div>
                            <p class="text-sm text-gray-600">Anzahl Sparpläne</p>
                            <p class="text-xl font-semibold">{{ $savingsPlans->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Gesamtzielbetrag</p>
                            <p class="text-xl font-semibold">€ {{ number_format($totalTargetAmount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Aktueller Gesamtbetrag</p>
                            <p class="text-xl font-semibold">€ {{ number_format($totalCurrentAmount, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <!-- Button to toggle form -->
                        <button
                            @click="showForm = !showForm"
                            class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                            Neue Sparplan
                        </button>
                    </div>
                </div>

                <!-- Formular zum Hinzufügen eines Sparplans -->
                <div x-show="showForm" class="p-4">
                    <form action="{{ route('savings.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="target_amount" class="block text-sm font-medium text-gray-700">Zielbetrag (€)</label>
                            <input type="number" step="0.01" name="target_amount" id="target_amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="current_amount" class="block text-sm font-medium text-gray-700">Aktueller Betrag (€)</label>
                            <input type="number" step="0.01" name="current_amount" id="current_amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">
                            Sparplan speichern
                        </button>
                        <button
                            type="button"
                            @click="showForm = false"
                            class="ml-2 bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">
                            Abbrechen
                        </button>
                    </form>
                </div>
            </div>
            <!-- Sparpläne Liste -->
            <div x-data="{ editingPlanId: null }">
                @foreach($savingsPlans as $plan)
                <div class="bg-white p-4 rounded-lg shadow mb-4 relative">
                    <div class="absolute top-2 right-2 flex space-x-2">
                        <!-- Bearbeiten-Button -->
                        <button
                            @click="editingPlanId = {{ $plan->id }}"
                            class="text-yellow-500 hover:text-yellow-600">
                            <!-- Bearbeiten-Icon -->
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

                        <!-- Löschen-Button -->
                        <form action="{{ route('savings.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Möchten Sie diesen Sparplan wirklich löschen?');">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="text-red-500 hover:text-red-600 pt-1">
                                <!-- Löschen-Icon -->
                                <svg class="h-5 w-5" viewBox="0 0 1024 1024" fill="red" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M32 241.6c-11.2 0-20-8.8-20-20s8.8-20 20-20l940 1.6c11.2 0 20 8.8 20 20s-8.8 20-20 20L32 241.6zM186.4 282.4c0-11.2 8.8-20 20-20s20 8.8 20 20v688.8l585.6-6.4V289.6c0-11.2 8.8-20 20-20s20 8.8 20 20v716.8l-666.4 7.2V282.4z" fill="" />
                                    <path d="M682.4 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM367.2 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM524.8 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM655.2 213.6v-48.8c0-17.6-14.4-32-32-32H418.4c-18.4 0-32 14.4-32 32.8V208h-40v-42.4c0-40 32.8-72.8 72.8-72.8H624c40 0 72.8 32.8 72.8 72.8v48.8h-41.6z" fill="" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    <h4 class="text-lg font-semibold">{{ $plan->name }}</h4>
                    <p class="text-sm text-gray-600">Ziel: € {{ number_format($plan->target_amount, 2) }}</p>
                    <p class="text-sm text-gray-600">Aktuell: € {{ number_format($plan->current_amount, 2) }}</p>

                    <div x-data="{ progress: {{ $plan->current_amount / $plan->target_amount * 100 }} }">
                        <div class="bg-gray-200 h-4 rounded-lg overflow-hidden mt-2">
                            <div class="bg-blue-500 h-full" :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>

                    <!-- Bearbeiten-Modus -->
                    <div x-show="editingPlanId === {{ $plan->id }}" x-cloak>
                        <form action="{{ route('savings.update', $plan->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="editName{{ $plan->id }}" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="editName{{ $plan->id }}" name="name" value="{{ $plan->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="editTargetAmount{{ $plan->id }}" class="block text-sm font-medium text-gray-700">Zielbetrag (€)</label>
                                <input type="number" id="editTargetAmount{{ $plan->id }}" name="target_amount" step="0.01" value="{{ $plan->target_amount }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="editCurrentAmount{{ $plan->id }}" class="block text-sm font-medium text-gray-700">Aktueller Betrag (€)</label>
                                <input type="number" id="editCurrentAmount{{ $plan->id }}" name="current_amount" step="0.01" value="{{ $plan->current_amount }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <!-- Abbrechen-Button -->
                                <button
                                    type="button"
                                    @click="editingPlanId = null"
                                    class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">
                                    Abbrechen
                                </button>
                                <!-- Speichern-Button -->
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">
                                    Speichern
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
</x-app-layout>