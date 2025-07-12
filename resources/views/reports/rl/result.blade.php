<x-app-layout>
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">
            Relatório de Cirurgias do Cirurgião: 
            <span class="text-blue-500">
                {{ $surgeriesReport->first()['surgeon_name'] ?? 'N/A' }}
            </span>
        </h2>

        <!-- Verificação de dados -->
        @if ($surgeriesReport->count())
            <!-- Tabela de resultados -->
            <table class="min-w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Cirurgião</th>
                        <th class="py-3 px-6 text-left">Nome do Paciente</th>
                        <th class="py-3 px-6 text-left">Nome da Cirurgia</th>
                        <th class="py-3 px-6 text-left">Data</th>
                        <th class="py-3 px-6 text-left">Hora</th>
                        <th class="py-3 px-6 text-left">Total de Cirurgias</th>
                        <th class="py-3 px-6 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 dark:text-gray-400 text-sm font-light">
                    @foreach ($surgeriesReport as $report)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $report['surgeon_name'] }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $report['patient_name'] }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $report['surgery_type'] ?? 'N/A' }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ \Carbon\Carbon::parse($report['surgery_date'])->format('d/m/Y') }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $report['surgery_time'] }}</td>
                            <td class="py-3 px-6 text-left">{{ $report['surgery_count'] }}</td>
                            <td class="py-3 px-6 text-center">
                                <a href="" 
                                {{-- <a href="{{ route('surgeries.show', $report['surgery_id'] ?? '#') }}"  --}}
                                   class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 focus:ring focus:ring-blue-300">
                                    Detalhes
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <!-- Mensagem caso não tenha dados -->
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow-md">
                <p class="text-center">Nenhuma cirurgia encontrada para o cirurgião selecionado.</p>
            </div>
        @endif

        <!-- Links de Paginação -->
        <div class="mt-4">
            {{ $surgeriesReport->links() }}
        </div>
    </div>
</x-app-layout>
