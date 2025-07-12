<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Cirurgias</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">Dashboard de Cirurgias</h1>

        <!-- Formulário para solicitar gráficos -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Solicitar Gráfico</h2>
            <form method="POST" action="{{ route('dashboard.surgeries.chart') }}" class="space-y-4">
                @csrf
                <textarea 
                    name="chart_prompt" 
                    rows="4" 
                    class="w-full border border-gray-300 rounded-lg p-4" 
                    placeholder="Exemplo: 'Quero um gráfico de barras mostrando o número de cirurgias por tipo de anestesia.'"
                    required></textarea>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Gerar Gráfico</button>
            </form>
        </div>

        <!-- Gráfico Dinâmico -->
        @if($chartConfig)
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Gráfico Gerado</h2>
            <canvas id="dynamicChart"></canvas>
        </div>
        @endif
    </div>

    @if($chartConfig)
    <script>
        @if($chartConfig)
            try {
                const chartConfig = JSON.parse(@json($chartConfig));
                console.log("Configuração do Gráfico:", chartConfig);
                const ctx = document.getElementById('dynamicChart').getContext('2d');
                new Chart(ctx, chartConfig);
            } catch (error) {
                console.error("Erro ao processar o gráfico:", error);
            }
        @endif
    </script>
    
    @endif
</body>
</html>
