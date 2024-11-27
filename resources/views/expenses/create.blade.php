<h2 class="text-xl font-bold mb-4">Ausgabe hinzufügen</h2>
<form action="{{ route('expenses.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label for="amount" class="block text-sm font-medium text-gray-700">Betrag</label>
        <input type="number" step="0.01" name="amount" id="amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div class="mb-4">
        <label for="category" class="block text-sm font-medium text-gray-700">Kategorie</label>
        <select name="category_id" id="expense_category" class="w-full border rounded px-2 py-1">
            <option value="" disabled selected>Kategorie wählen</option>
            @foreach ($expenseCategories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
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