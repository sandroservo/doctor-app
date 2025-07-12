<x-app-layout> 
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8" x-data="{ showModal: false, deleteUrl: '' }">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-lg">
            
            <!-- Título e Botão Voltar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                    Indicações
                </h2>
                <a href="{{ url()->previous() }}" class="mt-3 sm:mt-0 px-4 py-2 bg-gray-500 text-white text-sm rounded-lg shadow-md hover:bg-gray-600 transition">
                    🔙 Voltar
                </a>
            </div>

            <!-- Botão Cadastrar -->
            <div class="flex justify-center sm:justify-start">
                <a href="{{ route('indications.create') }}"
                   class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-600 transition">
                    ➕ Cadastrar Nova Indicação
                </a>
            </div>

            <!-- Mensagem de Sucesso -->
            @if(session('success'))
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabela Responsiva -->
            <div class="overflow-x-auto mt-6">
                <table class="w-full border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase text-sm">
                        <tr>
                            <th class="py-3 px-4 text-left">Descrição</th>
                            <th class="py-3 px-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 dark:text-gray-400 text-sm">
                        @forelse ($indications as $indication)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                <td class="py-3 px-4">{{ $indication->descricao }}</td>
                                <td class="py-3 px-4 flex justify-center space-x-4">
                                    <a href="{{ route('indications.edit', $indication->id) }}"
                                       class="text-blue-500 hover:text-blue-600 transition">
                                        ✏️ Editar
                                    </a>
                                    <button @click="showModal = true; deleteUrl = '{{ route('indications.destroy', $indication->id) }}'"
                                            class="text-red-500 hover:text-red-600 transition">
                                        ❌ Excluir
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4">Nenhuma indicação encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $indications->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50 transition-opacity" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Confirmação de Exclusão</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Tem certeza de que deseja excluir esta indicação?</p>
            <div class="flex justify-end space-x-4">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Cancelar
                </button>
                <form :action="deleteUrl" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
