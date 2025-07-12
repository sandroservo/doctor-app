<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 bg-gray-900 text-gray-100">
        <!-- Título -->
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold">Relatório de Cirurgias com APGAR Abaixo de 7</h1>
            <span class="text-sm font-semibold bg-gray-700 text-gray-300 px-4 py-2 rounded-md">
                Total de Cirurgias: {{ $totalSurgeries }}
            </span>
        </div>

        <!-- Tabela -->
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Cirurgião
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Descrição da Cirurgia
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Total de Cirurgias
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Detalhes
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Profissionais Envolvidos
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse ($surgeries as $surgery)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100">
                                {{ $surgery->surgeon_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $surgery->surgery_description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $surgery->total_low_apgar }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <button type="button" onclick="toggleDetails({{ $loop->index }})"
                                    class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-md">
                                    Ver Detalhes
                                </button>
                                <div id="details-{{ $loop->index }}" class="mt-2 hidden text-gray-300">
                                    <ul class="list-disc pl-5">
                                        @foreach (explode(',', $surgery->surgery_details) as $detail)
                                            <li>{{ $detail }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <strong>Anestesistas:</strong> {{ $surgery->anesthetists ?? 'N/A' }}<br>
                                <strong>Enfermeiros:</strong> {{ $surgery->nurses ?? 'N/A' }}<br>
                                <strong>Pediatras:</strong> {{ $surgery->pediatricians ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                Nenhum dado encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $surgeries->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Script para exibir detalhes -->
    <script>
        function toggleDetails(index) {
            const details = document.getElementById(`details-${index}`);
            if (details) {
                details.classList.toggle('hidden');
            }
        }
    </script>
</x-app-layout>
