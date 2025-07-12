<!-- resources/views/reports/surgeries_by_surgeon.blade.php -->
<x-app-layout>
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Relatório de Cirurgias do Mês por Cirurgião
        </h2>

        <!-- Botão para gerar o PDF -->
        <a href="{{ route('reports.surgeries_by_surgeon', ['pdf' => 1]) }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 mb-4 inline-block">
            Gerar PDF
        </a>

        <!-- Tabela de cirurgias por cirurgião -->
        @foreach ($surgeries as $surgeonId => $surgeriesBySurgeon)
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mt-6">
                {{ $surgeriesBySurgeon->first()->cirurgiao->name ?? 'Cirurgião Não Informado' }}
            </h3>
            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 mt-2">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Tipo de Cirurgia</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($surgeriesBySurgeon as $surgery)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($surgery->time)->format('H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $surgery->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $surgery->surgery_type ?? 'Não especificado' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</x-app-layout>
