<!-- resources/views/reports/total_surgeries.blade.php -->
<x-app-layout>
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Relatório Geral de Cirurgias do Mês</h2>

        <!-- Botão para gerar o PDF -->
        <a href="{{ route('reports.total_surgeries', ['pdf' => 1]) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 mb-4 inline-block">
            Gerar PDF
        </a>

        <!-- Tabela de cirurgias -->
        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 mt-4">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Data</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Paciente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Idade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cirurgião</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Enfermeiro</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Anestesista</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Pediatra</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($surgeries as $surgery)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($surgery->time)->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $surgery->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $surgery->age }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $surgery->cirurgiao->name ?? 'Não informado' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $surgery->enfermeiro->name ?? 'Não informado' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $surgery->anestesista->name ?? 'Não informado' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $surgery->pediatra->name ?? 'Não informado' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
