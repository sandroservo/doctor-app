
<x-app-layout>
    <div class="container mx-auto py-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100"> Relatório de Cirurgias por Mês</h2>

        <form id="reportForm">
            @csrf
            <div class="mb-4 flex items-end space-x-4">
                <div class="flex-1">
                    <label for="surgery_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Cirurgia:</label>
                    <select name="surgery_type" id="surgery_type" class="form-select mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required>
                        <option value="">Selecione o Tipo de Cirurgia</option>
                        @foreach ($surgeryTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->descricao }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mês:</label>
                    <select name="month" id="month" class="form-select mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required>
                        <option value="">Selecione o Mês</option>
                        <option value="1">Janeiro</option>
                        <option value="2">Fevereiro</option>
                        <option value="3">Março</option>
                        <option value="4">Abril</option>
                        <option value="5">Maio</option>
                        <option value="6">Junho</option>
                        <option value="7">Julho</option>
                        <option value="8">Agosto</option>
                        <option value="9">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                    </select>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded dark:bg-blue-600 dark:hover:bg-blue-700">
                    Gerar PDF
                </button>
            </div>
        </form>

        <!-- Área para exibir o PDF -->
        <div id="pdfViewer" class="mt-8" style="display: none;">
            <iframe id="pdfFrame" src="" width="100%" height="600px" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            fetch("{{ route('reports.generatePdf') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Verifica se a URL do PDF foi retornada
                if (data.url) {
                    document.getElementById('pdfViewer').style.display = 'block';
                    document.getElementById('pdfFrame').src = data.url;
                } else {
                    alert('Erro ao gerar o PDF');
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                alert('Erro ao gerar o PDF');
            });
        });
    </script>
</x-app-layout>

