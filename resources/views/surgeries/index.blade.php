<x-app-layout>
    <div class="container mx-auto p-4 sm:p-6 bg-gray-900 text-gray-200 min-h-screen">
        <!-- Header -->
        <!-- Container principal -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-2">
            <!-- Título centralizado no mobile e alinhado à esquerda no desktop -->
            <h1 class="text-2xl font-bold text-gray-200 text-center sm:text-left">📋 Cirurgias</h1>

            <!-- Campo de Pesquisa e Botão Adicionar -->
            <div class="flex items-center w-full sm:w-auto space-x-2">
                <form method="GET" action="{{ route('surgeries.index') }}"
                    class="flex bg-gray-800 rounded-lg overflow-hidden flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Buscar"
                        class="bg-gray-800 text-gray-200 border-none px-4 py-2 w-full placeholder-gray-400 focus:outline-none">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">
                        Buscar
                    </button>
                </form>

                <!-- Botão Adicionar: Ícone + no Mobile, Botão Completo no Desktop -->
                <a href="{{ route('surgeries.create') }}"
                    class="bg-green-500 text-white p-3 rounded-full shadow-md hover:bg-green-600 transition-all flex justify-center items-center sm:rounded-md sm:px-4 sm:py-2 sm:space-x-2">
                    <span class="text-xl font-bold">+</span>
                    <span class="hidden sm:inline">Adicionar</span>
                </a>
            </div>
        </div>


        <!-- Mensagem de Sucesso -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md shadow-md mb-6 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Lista Responsiva -->
        <div class="space-y-4">
            @foreach ($surgeryRecord as $record)
                @php
                    $isAfterSixPM =
                        !empty($record->time) &&
                        \Carbon\Carbon::createFromFormat('H:i:s', $record->time)->gte(
                            \Carbon\Carbon::createFromTime(18, 0),
                        );
                @endphp

                <div
                    class="bg-gray-800 rounded-lg shadow p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-blue-400">{{ $record->name }}</h2>
                        <p class="text-sm text-gray-400">
                            📅 {{ $record->date->format('d/m/Y') }} | 🕒
                            <span class="{{ $isAfterSixPM ? 'text-red-500' : 'text-gray-400' }}">
                                {{ $record->time }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-400">📍 {{ $record->city->name ?? 'Cidade não definida' }} -
                            {{ $record->state->name ?? 'Estado não definido' }}</p>
                    </div>

                    <!-- Ações -->
                    <div class="flex space-x-3 mt-4 sm:mt-0">
                        <a href="{{ route('surgeries.report', $record->id) }}" class="text-red-400 hover:text-red-600">
                            <i class="fas fa-file-pdf"></i> Detalhes
                        </a>
                        <a href="{{ route('surgeries.edit', $record->id) }}"
                            class="text-indigo-400 hover:text-indigo-600">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <button onclick="openModal({{ $record->id }})" class="text-red-400 hover:text-red-600">
                            <i class="fas fa-trash"></i> Excluir
                        </button>

                        <!-- Formulário oculto para exclusão -->
                        <form id="delete-form-{{ $record->id }}"
                            action="{{ route('surgeries.destroy', $record->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $surgeryRecord->appends(['search' => request('search')])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
            <h3 class="text-lg font-semibold text-gray-100">Confirmar Exclusão</h3>
            <p class="text-gray-400 mt-2">Tem certeza de que deseja excluir esta cirurgia? Essa ação não pode ser
                desfeita.</p>

            <div class="mt-4 flex justify-center space-x-4">
                <button id="confirmDelete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
                    Sim, excluir
                </button>
                <button onclick="closeModal()"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <script>
        let formId = null;

        function openModal(id) {
            formId = id;
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        function closeModal() {
            formId = null;
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (formId) {
                document.getElementById('delete-form-' + formId).submit();
            }
        });
    </script>
</x-app-layout>
