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
                <h3 class="font-bold text-lg mb-4">Einnahmen</h3>
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
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $income->date }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $income->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">€ {{ number_format($income->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $income->description }}</td>
                            <td class="px-6 py-4 text-sm flex space-x-2">
                                <!-- Bearbeiten Button -->
                                <button @click="editingId = {{ $income->id }}" class="text-blue-600 hover:underline">
                                    Bearbeiten
                                </button>
                                <!-- Löschen Button -->
                                <form action="{{ route('incomes.destroy', $income->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Löschen</button>
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
                                    <button type="submit" class="text-green-600 hover:underline">Speichern</button>
                                    <button type="button" @click="editingId = null" class="text-gray-600 hover:underline">Abbrechen</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Ausgaben Tab -->
            <div x-show="activeTab === 'expense'" class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="font-bold text-lg mb-4">Ausgaben</h3>
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
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $expense->date }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $expense->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">€ {{ number_format($expense->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $expense->description }}</td>
                            <td class="px-6 py-4 text-sm flex space-x-2">
                                <!-- Bearbeiten Button -->
                                <button @click="editingId = {{ $expense->id }}" class="text-blue-600 hover:underline">
                                    Bearbeiten
                                </button>
                                <!-- Löschen Button -->
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Löschen</button>
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