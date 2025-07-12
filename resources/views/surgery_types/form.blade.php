<x-app-layout>
    <div class="container mx-auto py-6 px-4">
        <!-- Botão Voltar -->
        <div class="mb-6">
            <a href="{{ url()->previous() }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-gray-600 transition">
                🔙 Voltar
            </a>
        </div>

        <!-- Título -->
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center">
            {{ isset($surgeryType) ? '✏️ Editar Tipo de Cirurgia' : '➕ Cadastrar Novo Tipo de Cirurgia' }}
        </h2>

        <!-- Mensagem de sucesso -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md shadow-sm mb-4" role="alert">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Formulário -->
        <form action="{{ isset($surgeryType) ? route('surgery_types.update', $surgeryType->id) : route('surgery_types.store') }}"
              method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md space-y-6">
            @csrf
            @if(isset($surgeryType))
                @method('PUT')
            @endif

            <!-- Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                    <input type="text" id="descricao" name="descricao" required
                        class="mt-1 block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        value="{{ isset($surgeryType) ? $surgeryType->descricao : old('descricao') }}">
                </div>

                <!-- Tipo de Cirurgia -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Cirurgia</label>
                    <select id="tipo" name="tipo" required
                        class="mt-1 block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>Selecione o tipo de cirurgia</option>
                        <option value="Obstetrica" {{ (isset($surgeryType) && $surgeryType->tipo == 'Obstetrica') ? 'selected' : '' }}>🍼 Cirurgia Obstétrica</option>
                        <option value="Ginecologica" {{ (isset($surgeryType) && $surgeryType->tipo == 'Ginecologica') ? 'selected' : '' }}>♀️ Cirurgia Ginecológica</option>
                        <option value="Pediatrica" {{ (isset($surgeryType) && $surgeryType->tipo == 'Pediatrica') ? 'selected' : '' }}>👶 Cirurgia Pediátrica</option>
                        <option value="Ortopedica" {{ (isset($surgeryType) && $surgeryType->tipo == 'Ortopedica') ? 'selected' : '' }}>🦴 Cirurgia Ortopédica</option>
                        <option value="Geral" {{ (isset($surgeryType) && $surgeryType->tipo == 'Geral') ? 'selected' : '' }}>⚕️ Cirurgia Geral</option>
                    </select>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-col md:flex-row justify-between space-y-3 md:space-y-0">
                <button type="submit"
                    class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                    {{ isset($surgeryType) ? '💾 Salvar Alterações' : '✅ Cadastrar' }}
                </button>
                <a href="{{ route('surgery_types.index') }}"
                    class="w-full md:w-auto px-6 py-3 bg-gray-500 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-gray-600 transition text-center">
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
