<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 bg-gray-900 text-gray-100">
        <!-- Título e Total de Cirurgias -->
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold">Relatório de Cirurgias por Cidade</h1>
            <span class="text-lg font-semibold bg-gray-700 text-gray-300 px-4 py-2 rounded-md">
                Total de Cirurgias: {{ $totalSurgeries }}
            </span>
        </div>

        <!-- Tabela de Dados -->
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Cidade
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Total de Cirurgias
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Percentual
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse ($surgeries as $surgery)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100">
                                {{ $surgery->city_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $surgery->total_surgeries }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $surgery->percentage }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <form method="GET" action="{{ route('reports.rl04.details', ['city_id' => $surgery->citie_id]) }}">
                                    <button type="submit"
                                        class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-md">
                                        Detalhes
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
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
</x-app-layout>
