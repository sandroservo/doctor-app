<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 bg-gray-900 text-gray-100">
        <h1 class="text-2xl font-bold mb-5">Relatório de Cirurgias  do  mês  especifidacada por CIRURGIÃO - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h1>

        <form method="GET" action="{{ route('reports.rl02') }}" class="mb-5 flex items-center">
            <label for="month" class="block text-sm font-medium text-gray-300 mr-4">Selecione o mês:</label>
            <input 
                type="month" 
                id="month" 
                name="month" 
                value="{{ $month }}" 
                class="mt-1 block w-1/3 p-2 border border-gray-600 rounded-md bg-gray-800 text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <button 
                type="submit" 
                class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Filtrar
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-800 border border-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Horário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nome do Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cirurgião</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Profissionais Envolvidos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($surgeries as $surgery)
                        <tr class="border-b border-gray-700">
                            <td class="px-6 py-4 text-gray-100">{{ date('d/m/Y', strtotime($surgery->data)) }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->horario }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->descricao }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->cirurgiao }}</td>
                            <td class="px-6 py-4 text-gray-100">
                                <ul class="list-disc pl-5">
                                    @if ($surgery->anestesista)
                                        <li>Anestesista: {{ $surgery->anestesista }}</li>
                                    @endif
                                    @if ($surgery->enfermeiro)
                                        <li>Enfermeiro: {{ $surgery->enfermeiro }}</li>
                                    @endif
                                    @if ($surgery->pediatra)
                                        <li>Pediatra: {{ $surgery->pediatra }}</li>
                                    @endif
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-gray-100">
                                <button 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md"
                                    onclick="toggleDetails('{{ $surgery->cirurgia_id }}')">
                                    Detalhes
                                </button>
                            </td>
                        </tr>
                        <!-- Detalhes -->
                        <tr id="details-{{ $surgery->cirurgia_id }}" class="hidden">
                            <td colspan="6" class="bg-gray-800 text-gray-300 p-4">
                                <strong>Idade:</strong> {{ $surgery->idade }} <br>
                                <strong>Cidade/Estado:</strong> {{ $surgery->cidade }}/{{ $surgery->estado }} <br>
                                <strong>Prontuário:</strong> {{ $surgery->prontuario }} <br>
                                <strong>Anestesia:</strong> {{ $surgery->anestesia }} <br>
                                <strong>Indicação:</strong> {{ $surgery->indicacao }} <br>
                                <strong>APGAR:</strong> {{ $surgery->apgar }} <br>
                                <strong>Ligadura:</strong> {{ $surgery->ligadura ? 'Sim' : 'Não' }} <br>
                                <strong>Data de Admissão:</strong> {{ $surgery->data_admissao }} <br>
                                <strong>Hora de Admissão:</strong> {{ $surgery->hora_admissao }} <br>
                                <strong>Hora de Término:</strong> {{ $surgery->hora_termino }} <br>
                                <strong>Status Social:</strong> {{ $surgery->social }} <br>
                                <strong>Tipo de Cirurgia:</strong> {{ $surgery->tipo_cirurgia }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Links de Paginação -->
        <div class="mt-5">
            {{ $surgeries->links('pagination::tailwind') }}
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            const row = document.getElementById(`details-${id}`);
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
