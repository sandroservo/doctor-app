<x-app-layout>
    <div class="container mx-auto p-6 bg-gray-900 text-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Dashboard de Cirurgias</h1>
            <div class="flex items-center space-x-2">
                <label for="startDate" class="text-sm font-semibold text-gray-400">De:</label>
                <input type="date" id="startDate" class="bg-gray-700 text-gray-200 border-gray-600 rounded px-2 py-1"
                    value="{{ request('start_date') }}">
                <label for="endDate" class="text-sm font-semibold text-gray-400">Até:</label>
                <input type="date" id="endDate" class="bg-gray-700 text-gray-200 border-gray-600 rounded px-2 py-1"
                    value="{{ request('end_date') }}">
                <button id="filterButton"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filtrar</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-4 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-blue-400">Total de Cirurgias</h2>
                    <p class="text-4xl font-bold mt-2">{{ $totalCirurgias }}</p>
                    {{-- <p class="text-sm text-gray-400 mt-1">Atualizado no período selecionado</p> --}}
                </div>
                <i class="fas fa-hospital text-blue-400 text-4xl"></i>
            </div>

            <!-- Card 2 -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-4 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-green-400">Cirurgias Obstétricas</h2>
                    <p class="text-4xl font-bold mt-2">{{ $cirurgiasObstetricas }}</p>
                    {{-- <p class="text-sm text-gray-400 mt-1">Taxa de Crescimento</p> --}}
                </div>
                <i class="fas fa-baby text-green-400 text-4xl"></i>
            </div>

            <!-- Card 3 -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-4 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-orange-400">Cirurgias Ginecológicas</h2>
                    <p class="text-4xl font-bold mt-2">{{ $cirurgiasGinecologicas }}</p>
                    {{-- <p class="text-sm text-gray-400 mt-1">No período selecionado</p> --}}
                </div>
                <i class="fas fa-female text-orange-400 text-4xl"></i>
            </div>

            <!-- Card 4 -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-4 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-red-400">APGAR abaixo de 7</h2>
                    <p class="text-4xl font-bold mt-2">{{ $apgarAbaixoDeSete }}</p>
                    {{-- <p class="text-sm text-gray-400 mt-1">No período selecionado</p> --}}
                </div>
                <i class="fas fa-heartbeat text-red-400 text-4xl"></i>
            </div>

            <!-- Card 5 -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-4 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-indigo-400">Cirurgias Pediátricas</h2>
                    <p class="text-4xl font-bold mt-2">{{ $cirurgiasPediatricas }}</p>
                    {{-- <p class="text-sm text-gray-400 mt-1">No período selecionado</p> --}}
                </div>
                <i class="fas fa-child text-indigo-400 text-4xl"></i>
            </div>

            <!-- Card 6 -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-4 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-yellow-400">Cirurgia Mais Realizada</h2>
                    <p class="text-4xl font-bold mt-2">{{ $cirurgiaMaisRealizada->tipo_cirurgia }}</p>
                    <p class="text-sm text-gray-400 mt-1">Total: {{ $cirurgiaMaisRealizada->total }}</p>
                </div>
                <i class="fas fa-star text-yellow-400 text-4xl"></i>
            </div>
        </div>
        <!-- Gráfico de Cirurgias por Cirurgião -->
        <style>
            #surgeriesBySurgeonChart {
                max-width: 1000px;
                /* Reduz a largura máxima */
                max-height: 800px;
                /* Reduz a altura máxima */
            }
        </style>

        {{-- <div class="mt-6 bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-400 mb-4">Cirurgias por Cirurgião</h2>
            <canvas id="surgeriesBySurgeonChart" class="w-3/4 mx-auto h-48"></canvas>
        </div> --}}

        <!-- Gráfico de Cirurgias por Cidade -->
        <div class="mt-6 bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-400 mb-4">Cirurgias por Cidade</h2>
            <canvas id="surgeriesByCityChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        document.getElementById('filterButton').addEventListener('click', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            const url = startDate && endDate ?
                `/dashboard2?start_date=${startDate}&end_date=${endDate}` :
                '/dashboard2';

            window.location.href = url;
        });


        document.addEventListener('DOMContentLoaded', function() {
            const data = @json($cirurgiasPorCidade);
            const labels = data.map(item => item.city);
            const counts = data.map(item => item.total);

            const ctx = document.getElementById('surgeriesByCityChart').getContext('2d');
            new Chart(ctx, {
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
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#e5e7eb'
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return value;
                            },
                            color: '#ffffff',
                            font: {
                                weight: 'bold',
                                size: 14
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
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dados do gráfico de cirurgias por cirurgião
            const surgeonData = @json($cirurgiasPorCirurgiao);
            const surgeonLabels = surgeonData.map(item => item.cirurgiao);
            const surgeonCounts = surgeonData.map(item => item.total);

            // Gráfico de Pizza
            const ctxSurgeon = document.getElementById('surgeriesBySurgeonChart').getContext('2d');
            new Chart(ctxSurgeon, {
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
                    maintainAspectRatio: false, // Permite ajustar o tamanho do gráfico
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#e5e7eb'
                                // Define a altura do retângulo da legenda
                               
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
