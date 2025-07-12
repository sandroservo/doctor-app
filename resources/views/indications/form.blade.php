<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">

            <!-- Cabeçalho e Botão de Voltar -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                    {{ isset($indication) ? 'Editar Indicação' : 'Cadastrar Nova Indicação' }}
                </h2>
                <a href="{{ route('indications.index') }}" class="px-4 py-2 bg-gray-500 text-white text-sm rounded-lg shadow-md hover:bg-gray-600 transition">
                    🔙 Voltar
                </a>
            </div>

            <!-- Formulário -->
            <form action="{{ isset($indication) ? route('indications.update', $indication->id) : route('indications.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($indication))
                    @method('PUT')
                @endif

                <!-- Campo de Descrição + Botão -->
                <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                    <div class="w-full">
                        <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                        <input type="text" id="descricao" name="descricao" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               value="{{ isset($indication) ? $indication->descricao : old('descricao') }}">
                    </div>
                    <button type="submit" class="mt-4 md:mt-7 px-6 py-3 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 transition">
                        💾 Salvar
                    </button>
                </div>

                <!-- Botões de Ação -->
                <div class="flex space-x-4 justify-center">
                    <button type="submit" class="w-full bg-green-500 text-white px-4 py-3 rounded-lg hover:bg-green-600 shadow-md transition">
                        {{ isset($indication) ? 'Atualizar Indicação' : 'Cadastrar' }}
                    </button>
                    <a href="{{ route('indications.index') }}" class="w-full bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 shadow-md transition text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
