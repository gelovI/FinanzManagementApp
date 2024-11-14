<x-app-layout>
    <x-slot name="main">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Einnahmen') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto py-12">
        <h2 class="text-xl font-bold mb-4">Einnahmen</h2>

        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Betrag</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategorie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beschreibung</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($incomes as $income)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">@formatDate($income->date)</td>
                    <td class="px-6 py-4 text-sm text-gray-900">€ {{ number_format($income->amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $income->category->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $income->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>