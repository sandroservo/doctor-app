<x-app-layout>
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">
            {{ isset($indication) ? 'Editar Indicação' : 'Cadastrar Nova Indicação' }}
        </h2>

        <form action="{{ isset($indication) ? route('indications.update', $indication->id) : route('indications.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($indication))
                @method('PUT')
            @endif

            <!-- Campo de Descrição da Indicação -->
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                <input type="text" id="descricao" name="descricao" class="form-input mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required
                    value="{{ isset($indication) ? $indication->descricao : old('descricao') }}">
            </div>

            <!-- Botões de Ação -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    {{ isset($indication) ? 'Salvar Alterações' : 'Cadastrar' }}
                </button>
                <a href="{{ route('indications.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
