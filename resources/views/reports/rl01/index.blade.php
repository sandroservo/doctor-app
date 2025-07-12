<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 bg-gray-900 text-gray-200">
        <h2 class="text-2xl font-semibold text-gray-200">Relatório de Cirurgias</h2>

        <form method="GET" action="{{ route('reports.surgeries') }}" class="mt-4 flex space-x-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Cirurgião -->
                <div>
                    <label for="cirurgiao_id" class="block text-sm font-medium text-gray-200">Cirurgião</label>
                    <select name="cirurgiao_id" id="cirurgiao_id" class="mt-1 block w-full border-gray-700 bg-gray-800 text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Selecione</option>
                        @foreach ($surgeons as $surgeon)
                            <option value="{{ $surgeon->id }}" {{ request('cirurgiao_id') == $surgeon->id ? 'selected' : '' }}>
                                {{ $surgeon->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Data Inicial -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-200">Data Inicial</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full border-gray-700 bg-gray-800 text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Data Final -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-200">Data Final</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full border-gray-700 bg-gray-800 text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="mt-4 flex space-x-4">
                <!-- Botão Filtrar -->
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700">
                    Filtrar
                </button>

                <!-- Botão Gerar PDF -->
                <button type="submit" name="show_pdf" value="1" class="px-4 py-2 bg-green-600 text-white rounded-md shadow hover:bg-green-700">
                    Gerar PDF
                </button>
            </div>
        </form>

        <!-- Tabela -->
        <div class="mt-8 overflow-hidden shadow ring-1 ring-gray-700 ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-200">Nome</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-200">Tipo de Cirurgia</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-200">Data</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-200">Horário</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-200">Cirurgião</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 bg-gray-900">
                    @forelse ($surgeries as $surgery)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-200">{{ $surgery->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-200">{{ $surgery->surgery_type->descricao }}</td>
                            <td class="px-6 py-4 text-sm text-gray-200">{{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-200">{{ $surgery->time }}</td>
                            <td class="px-6 py-4 text-sm text-gray-200">{{ $surgery->cirurgiao->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-200">Nenhuma cirurgia encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $surgeries->links('pagination::tailwind') }}
        </div>

        <!-- Exibição do PDF -->
        @if ($showPdf)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-200 mb-4">Visualização do Relatório em PDF</h2>
                <iframe src="{{ $showPdf }}" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
        @endif
    </div>
</x-app-layout>
