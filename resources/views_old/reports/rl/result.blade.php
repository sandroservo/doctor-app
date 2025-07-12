<x-app-layout>  
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">
            Relatório de Cirurgias do Cirurgião: {{ $surgeriesReport->first()['surgeon_name'] ?? 'N/A' }}
        </h2>

        <table class="min-w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Cirurgião</th>
                    <th class="py-3 px-6 text-left">Nome da Cirurgia</th>
                    <th class="py-3 px-6 text-left">Data</th>
                    <th class="py-3 px-6 text-left">Hora</th>
                    <th class="py-3 px-6 text-left">Total de Cirurgias</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-400 text-sm font-light">
                @forelse ($surgeriesReport as $report)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $report['surgeon_name'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $report['surgerytype'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ \Carbon\Carbon::parse($report['surgery_date'])->format('d/m/Y') }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $report['surgery_time'] }}</td>
                        <td class="py-3 px-6 text-left">{{ $report['surgery_count'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-600 dark:text-gray-400">
                            Nenhuma cirurgia encontrada para o cirurgião selecionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
