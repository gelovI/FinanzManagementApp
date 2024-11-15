<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistiken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Gesamteinnahmen und Ausgaben -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold">Einnahmen vs. Ausgaben</h3>
                <canvas id="incomeExpenseChart" class="mt-4"></canvas>
            </div>

            <!-- Fortschritt der Sparpläne -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold">Fortschritt der Sparpläne</h3>
                <canvas id="savingsChart" class="mt-4"></canvas>
            </div>

            <!-- Ausgaben nach Kategorien -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold">Ausgaben nach Kategorien</h3>
                <canvas id="categoryChart" class="mt-4"></canvas>
            </div>
        </div>
    </div>

    <!-- JavaScript für Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Einnahmen vs. Ausgaben
        fetch('/statistics/income-expense')
            .then(response => response.json())
            .then(data => {
                new Chart(document.getElementById('incomeExpenseChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Einnahmen', 'Ausgaben'],
                        datasets: [{
                            label: 'Beträge (€)',
                            data: [data.income, data.expense],
                            backgroundColor: ['#4CAF50', '#F44336'],
                        }],
                    },
                });
            });

        // Fortschritt der Sparpläne
        fetch('/statistics/savings')
            .then(response => response.json())
            .then(data => {
                new Chart(document.getElementById('savingsChart'), {
                    type: 'bar',
                    data: {
                        labels: data.map(plan => plan.name),
                        datasets: [{
                            label: 'Fortschritt (%)',
                            data: data.map(plan => (plan.current_amount / plan.target_amount) * 100),
                            backgroundColor: '#2196F3',
                        }],
                    },
                });
            });

        // Ausgaben nach Kategorien
        fetch('/statistics/categories')
            .then(response => response.json())
            .then(data => {
                new Chart(document.getElementById('categoryChart'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(data),
                        datasets: [{
                            label: 'Ausgaben (€)',
                            data: Object.values(data),
                            backgroundColor: ['#FF9800', '#9C27B0', '#00BCD4', '#FF5722', '#4CAF50'],
                        }],
                    },
                });
            });
    </script>
</x-app-layout>
