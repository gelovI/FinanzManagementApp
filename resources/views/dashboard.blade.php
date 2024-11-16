<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiken -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Gesamteinnahmen -->
                <div class="bg-green-100 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-green-800">Gesamteinnahmen</h3>
                    <p class="text-2xl font-bold text-green-900 mt-2">€ {{ number_format($totalIncome, 2) }}</p>
                </div>

                <!-- Gesamtausgaben -->
                <div class="bg-red-100 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-red-800">Gesamtausgaben</h3>
                    <p class="text-2xl font-bold text-red-900 mt-2">€ {{ number_format($totalExpense, 2) }}</p>
                </div>

                <!-- Saldo -->
                <div class="bg-blue-100 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-blue-800">Saldo</h3>
                    <p class="text-2xl font-bold text-blue-900 mt-2">€ {{ number_format($totalIncome - $totalExpense, 2) }}</p>
                </div>
            </div>

            <!-- Schnellzugriffe -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ showIncomeForm: false, showExpenseForm: false }">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold">Schnellzugriff</h3>
                    <div class="mt-4 flex space-x-4">
                        <!-- Buttons zum Anzeigen der Formulare -->
                        <button
                            class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600"
                            @click="showIncomeForm = !showIncomeForm; showExpenseForm = false">
                            Einnahme hinzufügen
                        </button>
                        <button
                            class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600"
                            @click="showExpenseForm = !showExpenseForm; showIncomeForm = false">
                            Ausgabe hinzufügen
                        </button>
                    </div>

                    <!-- Formular für Einnahmen -->
                    <div x-show="showIncomeForm" class="mt-6" x-cloak>
                        @include('incomes.create', ['categories' => $categories])
                    </div>

                    <!-- Formular für Ausgaben -->
                    <div x-show="showExpenseForm" class="mt-6" x-cloak>
                        @include('expenses.create', ['categories' => $categories])
                    </div>
                </div>
            </div>



            <!-- Letzte Transaktionen -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold">Letzte Transaktionen</h3>
                        <form method="GET" action="{{ route('dashboard') }}">
                            <select name="filter" id="filter" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">Alle</option>
                                <option value="30" {{ request('filter') == '30' ? 'selected' : '' }}>Letzte 30 Tage</option>
                                <option value="60" {{ request('filter') == '60' ? 'selected' : '' }}>Letzte 60 Tage</option>
                                <option value="180" {{ request('filter') == '180' ? 'selected' : '' }}>Letztes halbes Jahr</option>
                                <option value="365" {{ request('filter') == '365' ? 'selected' : '' }}>Letztes Jahr</option>
                            </select>
                        </form>
                    </div>
                    <div class="overflow-hidden shadow rounded-lg mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategorie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beschreibung</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Betrag</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">@formatDate($transaction->date)</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->category->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->description }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-right">€ {{ number_format($transaction->amount, 2) }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>