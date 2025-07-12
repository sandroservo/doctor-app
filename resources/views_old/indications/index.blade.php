<x-app-layout> 
    <div class="container mx-auto py-6" x-data="{ showModal: false, deleteUrl: '' }">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Indicações</h2>

        <a href="{{ route('indications.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 mb-4 inline-block">
            Cadastrar Nova Indicação
        </a>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <table class="min-w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Descrição</th>
                    <th class="py-3 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-400 text-sm font-light">
                @forelse ($indications as $indication)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="py-3 px-6">{{ $indication->descricao }}</td>
                        <td class="py-3 px-6 text-center">
                            <a href="{{ route('indications.edit', $indication->id) }}" class="text-blue-500 hover:text-blue-600">Editar</a>
                            <button @click="showModal = true; deleteUrl = '{{ route('indications.destroy', $indication->id) }}'" class="text-red-500 hover:text-red-600">
                                Excluir
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

        <!-- Links de Paginação -->
        <div class="mt-4">
            {{ $indications->links() }}
        </div>

        <!-- Modal de Confirmação -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50" style="display: none;">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirmação de Exclusão</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Tem certeza de que deseja excluir esta indicação?</p>
                <div class="flex justify-end space-x-4">
                    <button @click="showModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancelar
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
