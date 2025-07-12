<x-app-layout> 
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">
            {{ isset($surgeryType) ? 'Editar Tipo de Cirurgia' : 'Cadastrar Novo Tipo de Cirurgia' }}
        </h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ isset($surgeryType) ? route('surgery_types.update', $surgeryType->id) : route('surgery_types.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($surgeryType))
                @method('PUT')
            @endif

            <div class="flex flex-wrap space-x-4">
                <div class="w-full md:w-1/2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                    <input type="text" id="descricao" name="descricao" class="form-input mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required
                        value="{{ isset($surgeryType) ? $surgeryType->descricao : old('descricao') }}">
                </div>

                <div class="w-full md:w-1/2">
                    <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Cirurgia</label>
                    <select id="tipo" name="tipo" class="form-select mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required>
                        <option value="">Selecione o tipo de cirurgia</option>
                        <option value="Obstetrica" {{ (isset($surgeryType) && $surgeryType->tipo == 'Obstetrica') ? 'selected' : '' }}>Cirurgia Obstétrica</option>
                        <option value="Ginecologica" {{ (isset($surgeryType) && $surgeryType->tipo == 'Ginecologica') ? 'selected' : '' }}>Cirurgia Ginecológica</option>
                        <option value="Pediatrica" {{ (isset($surgeryType) && $surgeryType->tipo == 'Pediatrica') ? 'selected' : '' }}>Cirurgia Pediátrica</option>
                        <option value="Ortopedica" {{ (isset($surgeryType) && $surgeryType->tipo == 'Ortopedicas') ? 'selected' : '' }}>Cirurgia Ortopedica</option>
                        <option value="Geral" {{ (isset($surgeryType) && $surgeryType->tipo == 'Geral') ? 'selected' : '' }}>Cirurgia Geral</option>
                    </select>
                </div>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    {{ isset($surgeryType) ? 'Salvar Alterações' : 'Cadastrar' }}
                </button>
                <a href="{{ route('surgery_types.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
