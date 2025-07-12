<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 bg-gray-900 text-gray-100">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold">Detalhes das Cirurgias - {{ $city->name }}</h1>
            <form method="GET" action="{{ route('reports.rl04') }}">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-md">
                    Voltar
                </button>
            </form>
        </div>

        <!-- Tabela de Detalhes -->
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Tipo de Cirurgia
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Percentual
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse ($details as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100">
                                {{ $detail->surgery_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $detail->total_surgeries }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $detail->percentage }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                Nenhum detalhe encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $details->links('pagination::tailwind') }}
        </div>
    </div>
</x-app-layout>
