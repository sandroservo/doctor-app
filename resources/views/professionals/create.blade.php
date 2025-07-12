<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex items-center justify-center py-12 px-6">
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-lg">
            
            <!-- Título e descrição -->
            <div class="text-center">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                    Cadastro de Profissionais
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Preencha os dados do profissional abaixo.
                </p>
            </div>

            <!-- Formulário -->
            <form action="{{ route('professionals.store') }}" method="POST" class="mt-6 space-y-6">
                @csrf

                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nome Completo
                    </label>
                    <input type="text" id="name" name="name" required
                        class="mt-2 w-full px-4 py-3 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 transition duration-300"
                        placeholder="Digite o nome completo">
                </div>

                <!-- Especialidade e Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="specialty" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Especialidade
                        </label>
                        <select id="specialty" name="specialty" required
                            class="mt-2 w-full px-4 py-3 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 transition duration-300">
                            <option value="A">Anestesista</option>
                            <option value="C">Cirurgião</option>
                            <option value="P">Pediatra</option>
                            <option value="E">Enfermeiro</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>
                        <select id="status" name="status" required
                            class="mt-2 w-full px-4 py-3 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 transition duration-300">
                            <option value="Y">Ativo</option>
                            <option value="N">Inativo</option>
                        </select>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold rounded-md shadow-lg hover:from-blue-600 hover:to-purple-600 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-700 transition duration-300">
                        ✅ Cadastrar
                    </button>

                    <a href="{{ route('professionals.index') }}"
                        class="w-full sm:w-auto px-6 py-3 bg-gray-500 text-white font-bold rounded-md shadow-lg hover:bg-gray-600 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-700 transition duration-300 text-center">
                        🔙 Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
