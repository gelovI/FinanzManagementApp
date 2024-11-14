<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Einnahme hinzufügen') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <h2 class="text-xl font-bold mb-4">Einnahme hinzufügen</h2>
        <form action="{{ route('incomes.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">Betrag</label>
                <input type="number" step="0.01" name="amount" id="amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Kategorie</label>
                <select name="category_id" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @if ($categories->isEmpty())
                    <option value="">Keine Kategorien verfügbar</option>
                    @else
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Datum</label>
                <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600">Speichern</button>
        </form>
    </div>
</x-app-layout>