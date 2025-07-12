<x-app-layout>  
    <div class="container mx-auto py-6 px-4" x-data="{ showModal: false, deleteUrl: '' }">
        <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center md:text-left">
            🏥 Tipos de Cirurgias
        </h2>

        <div class="flex justify-center md:justify-start">
            <a href="{{ route('surgery_types.create') }}" 
                class="bg-green-600 text-white text-lg px-6 py-3 rounded-lg hover:bg-green-700 shadow-md transition">
                ➕ Novo Tipo de Cirurgia
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 text-lg px-4 py-3 rounded-md shadow-sm mt-4" role="alert">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Tabela desktop -->
        <div class="hidden md:block bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mt-6">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 uppercase text-lg">
                        <th class="py-4 px-6 text-left">Descrição</th>
                        <th class="py-4 px-6 text-left">Tipo de Cirurgia</th>
                        <th class="py-4 px-6 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-400 text-lg">
                    @forelse ($surgeryTypes as $surgeryType)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <td class="py-4 px-6">{{ $surgeryType->descricao }}</td>
                            <td class="py-4 px-6">{{ $surgeryType->tipo }}</td>
                            <td class="py-4 px-6 text-center space-x-3">
                                <a href="{{ route('surgery_types.edit', $surgeryType->id) }}" 
                                    class="text-blue-600 hover:text-blue-700 font-semibold">
                                    ✏️ Editar
                                </a>
                                <button @click="showModal = true; deleteUrl = '{{ route('surgery_types.destroy', $surgeryType->id) }}'"
                                    class="text-red-600 hover:text-red-700 font-semibold">
                                    🗑 Excluir
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
        </div>

        <!-- Cards no mobile -->
        <div class="block md:hidden space-y-4 mt-6">
            @forelse ($surgeryTypes as $surgeryType)
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md border border-gray-300 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        {{ $surgeryType->descricao }}
                    </h3>
                    <p class="text-gray-700 dark:text-gray-300 text-sm mb-3">
                        <strong>🩺 Tipo:</strong> {{ $surgeryType->tipo }}
                    </p>
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('surgery_types.edit', $surgeryType->id) }}" 
                            class="text-blue-600 hover:text-blue-700 font-semibold text-base">
                            ✏️ Editar
                        </a>
                        <button @click="showModal = true; deleteUrl = '{{ route('surgery_types.destroy', $surgeryType->id) }}'"
                            class="text-red-600 hover:text-red-700 font-semibold text-base">
                            🗑 Excluir
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-lg text-gray-600 dark:text-gray-400">
                    Nenhum tipo de cirurgia encontrado.
                </p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $surgeryTypes->links() }}
        </div>

        <!-- Modal de Confirmação -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-11/12 max-w-md mx-auto">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4 text-center">
                    ❌ Confirmar Exclusão
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg text-center">
                    Tem certeza de que deseja excluir este tipo de cirurgia?
                </p>
                <div class="flex justify-between">
                    <button @click="showModal = false" 
                        class="px-6 py-3 bg-gray-500 text-white text-lg rounded-lg hover:bg-gray-600 transition flex-1 mx-1">
                        ❌ Cancelar
                    </button>
                    <form :action="deleteUrl" method="POST" class="flex-1 mx-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white text-lg rounded-lg hover:bg-red-700 transition w-full">
                            🗑 Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
