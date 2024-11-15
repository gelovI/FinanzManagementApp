<x-app-layout>
    <style>
        [x-cloak] {
            display: none;
        }

        /* Begrenzung der Höhe der Diagramme */
        .chart-container {
            height: calc(50vh - 3rem); /* Dynamische Höhe, passt sich an die Hälfte des Bildschirms an */
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistiken') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiken Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Linke Seite: Einnahmen vs. Ausgaben -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Einnahmen vs. Ausgaben</h3>
                    <div class="chart-container">
                        <canvas id="incomeExpenseChart"></canvas>
                    </div>
                </div>

                <!-- Rechte Seite: Sparziele und Ausgaben nach Kategorien -->
                <div class="grid grid-rows-2 gap-4">
                    <!-- Sparziele Fortschritt -->
                    <div class="bg-white shadow-sm sm:rounded-lg p-4">
                        <h3 class="text-lg font-bold mb-2">Sparziele</h3>
                        <div class="chart-container">
                            <canvas id="savingsChart"></canvas>
                        </div>
                    </div>
                    <!-- Ausgaben nach Kategorien -->
                    <div class="bg-white shadow-sm sm:rounded-lg p-4">
                        <h3 class="text-lg font-bold mb-2">Ausgaben nach Kategorien</h3>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
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
