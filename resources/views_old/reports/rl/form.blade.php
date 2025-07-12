<x-app-layout>
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Relatório de Cirurgias por Cirurgião</h2>

        <!-- Formulário de filtros -->
        <form action="{{ route('reports.surgeries_by_surgeon.generate') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Seleção do cirurgião -->
            <div>
                <label for="surgeon_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cirurgião</label>
                <select id="surgeon_id" name="surgeon_id" class="form-select mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required>
                    <option value="">Selecione um cirurgião</option>
                    @foreach ($surgeons as $surgeon)
                        <option value="{{ $surgeon->id }}">{{ $surgeon->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Seleção do mês -->
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mês</label>
                <select id="month" name="month" class="form-select mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required>
                    <option value="">Selecione o mês</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                    @endfor
                </select>
            </div>

            <!-- Seleção do ano -->
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ano</label>
                <input type="number" id="year" name="year" class="form-input mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required min="1900" max="{{ date('Y') }}" value="{{ date('Y') }}">
            </div>

            <!-- Botão para gerar o relatório -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Gerar Relatório
                </button>
                <button type="submit" name="pdf" value="1" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
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
