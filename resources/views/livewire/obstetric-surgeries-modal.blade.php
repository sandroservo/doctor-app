<div>
    <!-- Botão para abrir o modal -->
    <button wire:click="openModal"
        class="mt-4 bg-green-600 hover:bg-green-700 px-4 py-2 rounded dark:bg-green-300 dark:hover:bg-green-400">
        Ver Todas
    </button>

    <!-- Modal -->
    @if ($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-1/2 p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold">Lista de Cirurgias Obstétricas</h2>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <hr class="my-4">

                <!-- Lista de Cirurgias -->
                <ul>
                    @forelse ($surgeries as $surgery)
                        <li class="mb-2 flex justify-between">
                            <span>{{ $surgery['name'] }}</span>
                            <span class="text-gray-500">{{ $surgery['date'] }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500">Nenhuma cirurgia encontrada.</li>
                    @endforelse
                </ul>

                <!-- Botão Fechar -->
                <div class="flex justify-end mt-4">
                    <button wire:click="closeModal"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

