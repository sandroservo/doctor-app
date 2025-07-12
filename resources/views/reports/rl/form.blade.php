<x-app-layout> 
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Relatório de Cirurgias por Cirurgiãvvv</h2>

        <!-- Formulário de filtros -->
        <form action="{{ route('reports.surgeries_by_surgeon.generate') }}" method="POST" class="flex flex-wrap items-end space-x-4">
            @csrf
            
            <!-- Seleção do cirurgião -->
            <div class="flex flex-col">
                <label for="surgeon_id" class="text-sm font-medium text-gray-700 dark:text-gray-300">Cirurgião</label>
                <select id="surgeon_id" name="surgeon_id" class="form-select mt-1 bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 w-52" required>
                    <option value="">Selecione um cirurgião</option>
                    @foreach ($surgeons as $surgeon)
                        <option value="{{ $surgeon->id }}">{{ $surgeon->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Data de início do período -->
            <div class="flex flex-col">
                <label for="start_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">Data Inicial</label>
                <input type="date" id="start_date" name="start_date" class="form-input mt-1 bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 w-36" required>
            </div>

            <!-- Data de fim do período -->
            <div class="flex flex-col">
                <label for="end_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">Data Final</label>
                <input type="date" id="end_date" name="end_date" class="form-input mt-1 bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 w-36" required>
            </div>

            <!-- Botões para gerar o relatório e PDF -->
            <div class="flex space-x-2 mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Gerar Relatório
                </button>
                <button type="submit" name="pdf" value="1" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 ml-4">
                    Gerar PDF
                </button>
            </div>
        </form>

        <!-- Exibição do PDF -->
        @if(isset($pdfUrl))
            <div class="mt-8">
                <h3 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Visualização do Relatório PDF</h3>
                <iframe src="{{ $pdfUrl }}" width="100%" height="600px" class="border-2 border-gray-300 dark:border-gray-600"></iframe>
            </div>
        @endif
    </div>
</x-app-layout>
