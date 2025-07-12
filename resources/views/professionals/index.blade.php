<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-lg">
            
            <!-- Mensagem de sucesso -->
            @if (session('success'))
                <div class="bg-green-500 text-white font-bold px-4 py-3 mb-6 rounded-lg text-center">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Cabeçalho e Ações -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white text-center sm:text-left">
                    📋 Lista de Profissionais
                </h2>

                <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <!-- Campo de pesquisa -->
                    <form action="{{ route('professionals.index') }}" method="GET" class="flex w-full sm:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full sm:w-64 px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                               placeholder="🔍 Buscar...">
                        <button type="submit"
                                class="ml-2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700">
                            Buscar
                        </button>
                    </form>

                    <!-- Botão Adicionar -->
                    <a href="{{ route('professionals.create') }}" 
                       class="px-4 py-2 bg-green-600 text-white font-semibold rounded-md shadow hover:bg-green-700">
                        ➕ Adicionar
                    </a>
                </div>
            </div>

            <!-- Tabela Responsiva -->
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead class="bg-gray-200 dark:bg-gray-700 sticky top-0">
                        <tr class="text-gray-800 dark:text-gray-300 uppercase text-sm">
                            <th class="py-3 px-4 text-left">Nome</th>
                            <th class="py-3 px-4 text-left">Especialidade</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                        @foreach ($professionals as $professional)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-3 px-4">{{ $professional->name }}</td>
                                <td class="py-3 px-4">
                                    {{ ['C' => 'Cirurgião', 'A' => 'Anestesista', 'E' => 'Enfermeiro', 'P' => 'Pediatra'][$professional->specialty] ?? 'Desconhecido' }}
                                </td>
                                <td class="py-3 px-4 {{ $professional->status == 'Y' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $professional->status == 'Y' ? 'Ativo' : 'Inativo' }}
                                </td>
                                <td class="py-3 px-4 text-center space-x-3">
                                    <a href="{{ route('professionals.edit', $professional->id) }}"
                                        class="text-blue-600 hover:text-blue-800">✏️ Editar</a>
                                    
                                    <button type="button" class="text-red-600 hover:text-red-800"
                                        onclick="openModal({{ $professional->id }});">🗑️ Excluir</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $professionals->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Exclusão -->
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-11/12 sm:w-96">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">⚠️ Confirmação</h2>
            <p class="text-gray-700 dark:text-gray-300 text-center mt-4">
                Tem certeza de que deseja excluir este profissional?
            </p>
            <div class="mt-6 flex justify-center space-x-3">
                <button type="button" onclick="closeModal();" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    ❌ Cancelar
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        🗑️ Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal(id) {
            const modal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/professionals/${id}`;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>
