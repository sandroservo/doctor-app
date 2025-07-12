<!-- resources/views/reports/select_report.blade.php --> 
<x-app-layout>
    <div class="container mx-auto py-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Selecionar Tipo de Relatório</h2>

        <form id="reportForm" action="#" method="GET" class="space-y-6">
            <!-- Dropdown de seleção de tipo de relatório -->
            <div>
                <label for="report_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Escolha o tipo de relatório:</label>
                <select id="report_type" name="report_type" class="form-select mt-1 block w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" required>
                    <option value="">Selecione um relatório</option>
                    <option value="{{ route('reports.surgery_percentage') }}">Relatório de Cirurgias Percentualizadas por Cidade</option>
                    {{-- <option value="{{ route('reports.surgeries_by_surgeon') }}">Relatório de Cirurgias do por Cirurgiões</option> --}}
                    <option value="{{ route('reports.obstetric_surgeries') }}">Cirurgias Obstétricas</option>
                    <option value="{{ route('reports.gynecologic_surgeries') }}">Cirurgias Ginecológicas</option>
                    <option value="{{ route('reports.pediatric_surgeries') }}">Cirurgias Pediátricas</option>
                    {{-- <option value="{{ route('reports.surgeries_by_surgeon') }}">Cirurgias por Cirurgiões com Horários</option> --}}
                    <option value="{{ route('reports.night_surgeries') }}">Cirurgias Noturnas Percentualizadas</option>
                    <option value="{{ route('reports.day_surgeries') }}">Cirurgias Diurnas Percentualizadas</option>
                    <option value="{{ route('reports.low_apgar_surgeries') }}">Cirurgias com APGAR Abaixo de 7 e Mecônio</option>
                    <option value="{{ route('reports.surgical_indications') }}">Indicações Cirúrgicas Percentualizadas</option>
                    <option value="{{ route('reports.surgeries_by_municipality') }}">Cirurgias por Municípios com Percentuais</option>
                </select>
            </div>

            <!-- Botão para gerar o relatório -->
            <div>
                <button type="submit" id="generateReport" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">
                    Gerar Relatório
                </button>
            </div>
        </form>

        <!-- Área para exibir o PDF -->
        <div id="pdfViewer" class="mt-8" style="display: none;">
            <h3 class="text-xl font-semibold mb-4">Relatório Gerado</h3>
            <iframe id="pdfFrame" src="" width="100%" height="600px" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        document.getElementById('reportForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const reportTypeUrl = document.getElementById('report_type').value;

        if (reportTypeUrl) {
            fetch(reportTypeUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    document.getElementById('pdfViewer').style.display = 'block';
                    document.getElementById('pdfFrame').src = data.url;
                } else {
                    alert('Erro ao gerar o relatório.');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar o relatório:', error);
                alert('Erro ao gerar o relatório. Tente novamente.');
            });
        } else {
            alert('Por favor, selecione um tipo de relatório.');
        }
    });
    </script>
</x-app-layout>
