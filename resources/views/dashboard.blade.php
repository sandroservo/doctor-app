<x-app-layout>
    <div class="container mx-auto p-4 sm:p-6 bg-gray-900 text-gray-200 min-h-screen">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-center md:text-left">📊 Dashboard de Cirurgias</h1>
            <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <label for="startDate" class="text-sm font-semibold text-gray-400">De:</label>
                <input type="date" id="startDate"
                    class="bg-gray-800 text-gray-200 border border-gray-700 rounded-lg px-3 py-2 w-full sm:w-auto"
                    value="{{ request('start_date') }}">

                <label for="endDate" class="text-sm font-semibold text-gray-400">Até:</label>
                <input type="date" id="endDate"
                    class="bg-gray-800 text-gray-200 border border-gray-700 rounded-lg px-3 py-2 w-full sm:w-auto"
                    value="{{ request('end_date') }}">

                <button id="filterButton"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg w-full sm:w-auto transition-all">
                    🔍 Filtrar
                </button>
            </div>
        </div>




        <!-- Grid Responsivo de Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Template para os Cards -->
            @php
                $cards = [
                    [
                        'title' => 'Total de Cirurgias',
                        'value' => $totalCirurgias,
                        'color' => 'blue',
                        'icon' => 'fas fa-hospital',
                    ],
                    [
                        'title' => 'Cirurgias Obstétricas',
                        'value' => $cirurgiasObstetricas,
                        'color' => 'green',
                        'icon' => 'fas fa-baby',
                    ],
                    [
                        'title' => 'Cirurgias Ginecológicas',
                        'value' => $cirurgiasGinecologicas,
                        'color' => 'orange',
                        'icon' => 'fas fa-female',
                    ],
                    [
                        'title' => 'APGAR abaixo de 7',
                        'value' => $apgarAbaixoDeSete,
                        'color' => 'red',
                        'icon' => 'fas fa-heartbeat',
                    ],
                    [
                        'title' => 'Cirurgias Pediátricas',
                        'value' => $cirurgiasPediatricas,
                        'color' => 'indigo',
                        'icon' => 'fas fa-child',
                    ],
                    [
                        'title' => 'Cirurgia Mais Realizada',
                        'value' => $cirurgiaMaisRealizada->tipo_cirurgia,
                        'extra' => "Total: {$cirurgiaMaisRealizada->total}",
                        'color' => 'yellow',
                        'icon' => 'fas fa-star',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div
                    class="bg-gray-800 rounded-xl shadow-md p-5 flex justify-between items-center transition-all transform hover:scale-105">
                    <div>
                        <h2 class="text-lg font-semibold text-{{ $card['color'] }}-400">{{ $card['title'] }}</h2>
                        <p class="text-3xl font-bold mt-2">{{ $card['value'] }}</p>
                        @isset($card['extra'])
                            <p class="text-sm text-gray-400 mt-1">{{ $card['extra'] }}</p>
                        @endisset
                    </div>
                    <i class="{{ $card['icon'] }} text-{{ $card['color'] }}-400 text-4xl"></i>
                </div>
            @endforeach
        </div>

        <!-- Gráficos Responsivos -->
        <div class="mt-6 bg-gray-800 rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-400 mb-4 text-center sm:text-left">📈 Cirurgias por Cirurgião
            </h2>
            <div class="w-full overflow-x-auto">
                <canvas id="surgeriesBySurgeonChart" class="w-full max-h-96"></canvas>
            </div>
        </div>

        <div class="mt-6 bg-gray-800 rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-400 mb-4 text-center sm:text-left">📊 Cirurgias por Cidade</h2>
            <div class="w-full overflow-x-auto">
                <canvas id="surgeriesByCityChart" class="w-full max-h-96"></canvas>
            </div>
        </div>
    </div>

    <!-- Scripts para Gráficos e Filtros -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById('filterButton').addEventListener('click', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const url = startDate && endDate ? `/dashboard?start_date=${startDate}&end_date=${endDate}` :
                '/dashboard';
            window.location.href = url;
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Cirurgias por Cidade
            const data = @json($cirurgiasPorCidade);
            const labels = data.map(item => item.city);
            const counts = data.map(item => item.total);

            new Chart(document.getElementById('surgeriesByCityChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Número de Cirurgias',
                        data: counts,
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                        borderColor: ['#1e40af', '#065f46', '#b45309', '#991b1b'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#e5e7eb'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#e5e7eb'
                            },
                            grid: {
                                color: '#374151'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#e5e7eb'
                            },
                            grid: {
                                color: '#374151'
                            }
                        }
                    }
                }
            });

            // Gráfico de Cirurgias por Cirurgião
            const surgeonData = @json($cirurgiasPorCirurgiao);
            const surgeonLabels = surgeonData.map(item => item.cirurgiao);
            const surgeonCounts = surgeonData.map(item => item.total);

            new Chart(document.getElementById('surgeriesBySurgeonChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: surgeonLabels.map((label, index) =>
                        `${label} (${surgeonCounts[index]})`), // Inclui o número de cirurgias
                    datasets: [{
                        label: 'Cirurgias por Cirurgião',
                        data: surgeonCounts,
                        backgroundColor: [
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6',
                            '#ec4899',
                            '#f43f5e'
                        ],
                        borderColor: [
                            '#1e40af',
                            '#065f46',
                            '#b45309',
                            '#991b1b',
                            '#6b21a8',
                            '#9d174d',
                            '#9f1239'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#e5e7eb'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
