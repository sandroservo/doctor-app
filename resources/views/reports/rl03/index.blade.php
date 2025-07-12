{{-- <x-app-layout>
    <div class="max-w-7xl mx-auto py-10 bg-gray-900 text-gray-100">
        <h1 class="text-2xl font-bold mb-5">Relatório de Cirurgias por Cirurgião</h1>

        <form method="GET" action="{{ route('reports.rl03') }}" class="mb-5 flex flex-wrap items-center">
            <label for="surgeon_id" class="block text-sm font-medium text-gray-300 mr-4">Nome do Cirurgião:</label>
            <select 
                id="surgeon_id" 
                name="surgeon_id" 
                class="mt-1 block w-1/3 p-2 border border-gray-600 rounded-md bg-gray-800 text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <option value="">Selecione um cirurgião</option>
                @foreach ($surgeons as $id => $name)
                    <option value="{{ $id }}" {{ $id == $surgeonId ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            
            <label for="month" class="block text-sm font-medium text-gray-300 ml-6 mr-4">Mês/Ano:</label>
            <input 
                type="month" 
                id="month" 
                name="month" 
                value="{{ $month }}" 
                class="mt-1 block w-1/4 p-2 border border-gray-600 rounded-md bg-gray-800 text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            
            <button 
                type="submit" 
                class="ml-6 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Filtrar
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-800 border border-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Horário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Prontuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cirurgião</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Anestesista</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pediatra</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Enfermeiro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($surgeries as $surgery)
                        <tr class="border-b border-gray-700">
                            <td class="px-6 py-4 text-gray-100">{{ \Carbon\Carbon::parse($surgery->data)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->horario }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->descricao }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->prontuario }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->cirurgiao }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->anestesista }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->pediatra }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->enfermeiro }}</td>
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
</x-app-layout> --}}

<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 bg-gray-900 text-gray-100">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold">Relatório de Cirurgias por Cirurgião</h1>
            <span class="text-lg font-semibold bg-gray-700 text-gray-300 px-4 py-2 rounded-md">
                Total de Cirurgias: {{ $totalSurgeries }}
            </span>
        </div>

        <form method="GET" action="{{ route('reports.rl03') }}" class="mb-5 flex flex-wrap items-center">
            <label for="surgeon_id" class="block text-sm font-medium text-gray-300 mr-4">Nome do Cirurgião:</label>
            <select 
                id="surgeon_id" 
                name="surgeon_id" 
                class="mt-1 block w-1/3 p-2 border border-gray-600 rounded-md bg-gray-800 text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <option value="">Selecione um cirurgião</option>
                @foreach ($surgeons as $id => $name)
                    <option value="{{ $id }}" {{ $id == $surgeonId ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            
            <label for="month" class="block text-sm font-medium text-gray-300 ml-6 mr-4">Mês/Ano:</label>
            <input 
                type="month" 
                id="month" 
                name="month" 
                value="{{ $month }}" 
                class="mt-1 block w-1/4 p-2 border border-gray-600 rounded-md bg-gray-800 text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            
            <button 
                type="submit" 
                class="ml-6 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Prontuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cirurgião</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($surgeries as $surgery)
                        <tr class="border-b border-gray-700">
                            <td class="px-6 py-4 text-gray-100">{{ \Carbon\Carbon::parse($surgery->data)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->horario }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->descricao }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->prontuario }}</td>
                            <td class="px-6 py-4 text-gray-100">{{ $surgery->cirurgiao }}</td>
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
</x-app-layout>

