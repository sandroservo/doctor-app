<x-app-layout>  
    <div class="container mx-auto py-6" x-data="{ showModal: false, deleteUrl: '' }">
        <h2 class="text-4xl font-bold mb-6 text-gray-900 dark:text-gray-100">Tipos de Cirurgias</h2>

        <a href="{{ route('surgery_types.create') }}" class="bg-green-500 text-white text-lg px-5 py-3 rounded-lg hover:bg-green-600 mb-4 inline-block">
            Cadastrar Novo Tipo de Cirurgia
        </a>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 text-lg px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <table class="min-w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 uppercase text-lg leading-normal">
                    <th class="py-4 px-6 text-left">Descrição</th>
                    <th class="py-4 px-6 text-left">Tipo de Cirurgia</th>
                    <th class="py-4 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 dark:text-gray-400 text-lg font-normal">
                @forelse ($surgeryTypes as $surgeryType)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="py-4 px-6">{{ $surgeryType->descricao }}</td>
                        <td class="py-4 px-6">{{ $surgeryType->tipo }}</td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('surgery_types.edit', $surgeryType->id) }}" class="text-blue-500 hover:text-blue-600 text-lg">Editar</a>
                            <button @click="showModal = true; deleteUrl = '{{ route('surgery_types.destroy', $surgeryType->id) }}'" class="text-red-500 hover:text-red-600 text-lg">
                                Excluir
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-lg">Nenhum tipo de cirurgia encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $surgeryTypes->links() }}
        </div>

        <!-- Modal de Confirmação -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md mx-auto">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirmação de Exclusão</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 text-lg">Tem certeza de que deseja excluir este tipo de cirurgia?</p>
                <div class="flex justify-end space-x-4">
                    <button @click="showModal = false" class="px-5 py-3 bg-gray-500 text-white text-lg rounded hover:bg-gray-600">
                        Cancelar
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-3 bg-red-500 text-white text-lg rounded hover:bg-red-600">
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
