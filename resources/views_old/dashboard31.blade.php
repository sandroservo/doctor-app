<x-app-layout>
    <div class="container mx-auto p-6 bg-gray-900 text-gray-200 dark:text-gray-800">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <h1 class="text-3xl text-white font-bold">Dashboard</h1>
                <form action="{{ route('dashboard3') }}" method="GET" class="flex items-center">
                    <input type="text" name="patient_name" placeholder="Buscar paciente"
                        value="{{ request('patient_name') }}"
                        class="bg-gray-700 text-gray-200 border-gray-600 rounded px-6 py-2 mr-4 w-96">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Pesquisar</button>
                </form>
            </div>
            <div class="flex items-center space-x-4">
                <form action="{{ route('dashboard3') }}" method="GET" class="flex items-center space-x-2">
                    <select name="surgeon_id" id="surgeonSelect"
                        class="bg-gray-700 text-gray-200 border-gray-600 rounded px-2 py-1">
                        <option value="">Selecione o cirurgião</option>
                        @foreach ($professionals as $professional)
                            <option value="{{ $professional->id }}"
                                {{ request('surgeon_id') == $professional->id ? 'selected' : '' }}>
                                {{ $professional->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="startDate" class="text-sm font-semibold">De:</label>
                    <input type="date" name="start_date" id="startDate" value="{{ request('start_date') }}"
                        class="bg-gray-700 text-gray-200 border-gray-600 rounded px-2 py-1">
                    <label for="endDate" class="text-sm font-semibold">Até:</label>
                    <input type="date" name="end_date" id="endDate" value="{{ request('end_date') }}"
                        class="bg-gray-700 text-gray-200 border-gray-600 rounded px-2 py-1">
                    <button type="submit" id="filterButton"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filtrar</button>
                </form>
                <div>
                    <button id="darkModeToggle"
                        class="bg-gray-700 p-2 rounded-full text-white hover:bg-gray-600 dark:bg-gray-300 dark:text-gray-900">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:block"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            {{-- <div
                class="bg-blue-500 text-white p-4 rounded-lg shadow-md dark:bg-blue-200 dark:text-gray-900 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">Cirurgias Realizadas</h2>
                    <p class="text-4xl font-bold">{{ $totalSurgeries }}</p>
                    <button
                        class="mt-4 bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded dark:bg-blue-300 dark:hover:bg-blue-400">Ver
                        Todas
                    </button>
                   

                </div>
                <i class="fas fa-hospital text-blue-400 text-4xl"></i>
            </div> --}}
            <div
                class="bg-blue-500 text-white p-4 rounded-lg shadow-md dark:bg-blue-200 dark:text-gray-900 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold mb-8">Cirurgias Realizadas</h2>
                    <p class="text-6xl font-extrabold">{{ $totalSurgeries }}</p>
                </div>
                <i class="fas fa-hospital text-blue-400 text-6xl ml-auto"></i>
            </div>

            <div
                class="bg-green-500 text-white p-4 rounded-lg shadow-md dark:bg-green-200 dark:text-gray-900 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">Cirurgias Obstétricas</h2>
                    <p class="text-4xl font-bold">{{ $totalObstetricSurgeries }}</p>
                    <button
                        class="mt-4 bg-green-600 hover:bg-green-700 px-4 py-2 rounded dark:bg-green-300 dark:hover:bg-green-400">
                        <a href="{{ route('dashboard3', array_merge(request()->query(), ['surgery_type' => 'Obstetrica'])) }}"
                            class="block"
                            class="mt-4 bg-green-600 hover:bg-green-700 px-2 py-2 rounded dark:bg-green-300 dark:hover:bg-green-400">
                            Ver Todas
                        </a>
                    </button>

                </div>
                <i class="fas fa-baby text-green-400 text-4xl"></i>
            </div>

            <div
                class="bg-yellow-500 text-white p-4 rounded-lg shadow-md dark:bg-yellow-200 dark:text-gray-900 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">Cirurgias Ginecológicas</h2>
                    <p class="text-4xl font-bold">{{ $totalcirurgiasGinecologicas }}</p>

                    <button
                        class="mt-4 bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded dark:bg-yellow-300 dark:hover:bg-yellow-400">
                        <a href="{{ route('dashboard3', array_merge(request()->query(), ['surgery_type' => 'Ginecologica'])) }}"
                            class="block"
                            class="mt-4 bg-yellow-600 hover:bg-yellow-700 px-2 py-2 rounded dark:bg-yellow-300 dark:hover:bg-yellow-400">
                            Ver Todas
                        </a>
                    </button>
                </div>
                <i class="fas fa-female text-orange-400 text-4xl"></i>
            </div>

            <div
                class="bg-red-500 text-white p-4 rounded-lg shadow-md dark:bg-red-200 dark:text-gray-900 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">Cirurgias Pediátricas</h2>
                    <p class="text-4xl font-bold">{{ $totalcirurgiasPediátricas }}</p>

                    <button
                        class="mt-4 bg-red-600 hover:bg-red-700 px-4 py-2 rounded dark:bg-red-300 dark:hover:bg-red-400">
                        <a href="{{ route('dashboard3', array_merge(request()->query(), ['surgery_type' => 'Pediátrica'])) }}"
                            class="block"
                            class="mt-4 bg-red-600 hover:bg-red-700 px-2 py-2 rounded dark:bg-red-300 dark:hover:bg-red-400">
                            Ver Todas
                        </a>
                    </button>
                </div>
                <i class="fas fa-heartbeat text-red-400 text-4xl"></i>
            </div>

        </div>

        <!-- Tabela de usuários -->
        <div class="bg-gray-800 p-4 rounded-lg shadow-md mb-6 dark:bg-gray-200 dark:text-gray-900">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold mb-4">Listagem de Todas as Cirurgias</h2>
                {{-- <a href="{{ route('dashboard3.pdf', ['surgeon_id' => $selectedSurgeonId, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                    target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Imprimir PDF
                </a> --}}
                <button id="printButton" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Imprimir PDF
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Data</th>
                            <th class="px-4 py-2">Hora</th>
                            <th class="px-4 py-2">Paciente</th>
                            <th class="px-4 py-2">Idade</th>
                            <th class="px-4 py-2">Cidade</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Anestesista</th>
                            <th class="px-4 py-2">Cirurgião</th>
                            <th class="px-4 py-2">Pediatra</th>
                            <th class="px-4 py-2">Enfermeiro</th>
                            <th class="px-4 py-2">Indicação</th>
                            <th class="px-4 py-2 whitespace-nowrap">Tipo de Cirurgia</th>
                            <th class="px-4 py-2">Prontuário</th>
                            <th class="px-4 py-2 whitespace-nowrap">Data de Admissão</th>
                            <th class="px-4 py-2 whitespace-nowrap">Hora de Admissão</th>
                            <th class="px-4 py-2">Origem</th>
                            <th class="px-4 py-2">Anestesia</th>
                            <th class="px-4 py-2">APGAR</th>
                            <th class="px-4 py-2 whitespace-nowrap">Hora Final</th>
                            <th class="px-4 py-2">Laqueadura</th>
                            <th class="px-4 py-2 whitespace-nowrap">Status Social</th>
                            <th class="px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($surgeries as $surgery)
                            <tr class="bg-gray-800 border-b border-gray-700">
                                <td class="px-4 py-2">{{ $surgery->id }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2">{{ $surgery->time }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $surgery->name }}</td>
                                <td class="px-4 py-2">{{ $surgery->age }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $surgery->city ? $surgery->city->name : 'N/A' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $surgery->state ? $surgery->state->name : 'N/A' }}</td>
                                <td class="px-4 py-2">
                                    {{ $surgery->anestesista ? $surgery->anestesista->name : 'N/A' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $surgery->cirurgiao ? $surgery->cirurgiao->name : 'N/A' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $surgery->pediatra ? $surgery->pediatra->name : 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $surgery->enfermeiro ? $surgery->enfermeiro->name : 'N/A' }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $surgery->indication ? $surgery->indication->descricao : 'N/A' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $surgery->surgery_type ? $surgery->surgery_type->descricao : 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $surgery->medical_record }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($surgery->admission_date)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $surgery->admission_time }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $surgery->origin_department }}</td>
                                <td class="px-4 py-2">{{ $surgery->anesthesia }}</td>
                                <td class="px-4 py-2">{{ $surgery->apgar }}</td>
                                <td class="px-4 py-2">{{ $surgery->end_time }}</td>
                                <td class="px-4 py-2">{{ $surgery->ligation ? 'Sim' : 'Não' }}</td>
                                <td class="px-4 py-2">{{ $surgery->social_status }}</td>
                                <td class="px-4 py-2">
                                    <button
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded openFlyout"
                                        data-id="{{ $surgery->id }}">Detalhes</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $surgeries->appends(request()->all())->links() }}
            </div>
        </div>

        {{-- <script>
            document.getElementById('printButton').addEventListener('click', function() {
                window.print();
            });
        </script> --}}


        <!-- Cards laterais -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div
                class="bg-yellow-600 text-white p-4 rounded-lg shadow-md flex items-center justify-between dark:bg-yellow-200 dark:text-gray-900">
                <span class="text-lg font-semibold">APGAR abaixo de 7</span>
                <span class="text-4xl font-bold">{{ $apgarAbaixoDeSete }}</span>
            </div>
            <div
                class="bg-gray-500 text-white p-4 rounded-lg shadow-md flex items-center justify-between dark:bg-gray-300 dark:text-gray-900">
                <div class="flex flex-col items-start">
                    <span class="text-lg font-semibold">Realizadas pela Manhã</span>
                    <span class="text-sm text-gray-200 dark:text-gray-700">das 07:00 às 11:59</span>
                </div>
                <span class="text-4xl font-bold">{{ $quantidadeCirurgiasManha }}</span>
            </div>
            <div
                class="bg-blue-500 text-white p-4 rounded-lg shadow-md flex items-center justify-between dark:bg-blue-200 dark:text-gray-900">
                <div class="flex flex-col items-start">
                    <span class="text-lg font-semibold">Realizadas à Tarde</span>
                    <span class="text-sm text-gray-200 dark:text-gray-700">das 12:00 às 18:59</span>
                </div>
                <span class="text-4xl font-bold">{{ $quantidadeCirurgiasTarde }}</span>
            </div>
            <div
                class="bg-pink-500 text-white p-4 rounded-lg shadow-md flex items-center justify-between dark:bg-pink-200 dark:text-gray-900">
                <div class="flex flex-col items-start">
                    <span class="text-lg font-semibold">Realizadas à Noite</span>
                    <span class="text-sm text-gray-200 dark:text-gray-700">das 19:00 às 23:59</span>
                </div>
                <span class="text-4xl font-bold">{{ $quantidadeCirurgiasNoite }}</span>
            </div>
            <div
                class="bg-pink-500 text-white p-4 rounded-lg shadow-md flex items-center justify-between dark:bg-pink-200 dark:text-gray-900">
                <div class="flex flex-col items-start">
                    <span class="text-lg font-semibold">Realizadas na Madrugada</span>
                    <span class="text-sm text-gray-200 dark:text-gray-700">das 00:00 às 05:59</span>
                </div>
                <span class="text-4xl font-bold">{{ $quantidadeCirurgiasMadrugda }}</span>
            </div>
            @if ($cidadeMaisAtendida)
                <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg text-black font-semibold">Cidade Mais Atendida</h3>
                    <p class="text-xl text-white font-bold">{{ $cidadeMaisAtendida->city }}</p>
                    <p>Total de Cirurgias: {{ $cidadeMaisAtendida->total }}</p>
                </div>
            @endif
            @if ($cirurgiaMaisRealizada)
                <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg text-black font-semibold">Cirurgia Mais Realizada</h3>
                    <p class="text-xl font-bold">{{ $cirurgiaMaisRealizada->descricao }}</p>
                    <p>Total de Realizações: {{ $cirurgiaMaisRealizada->total }}</p>
                </div>
            @endif
        </div>

        <!-- Espaçamento acima do card de Percentual -->
        @if (isset($percentualPorTipo) && $percentualPorTipo->isNotEmpty())
            <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-800">Percentual por Tipos</h3>
                    <select id="percentualSelector"
                        class="bg-gray-100 border border-gray-300 text-gray-700 text-base rounded px-6 py-2 w-72 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="cirurgia" selected>De Cirurgia</option>
                        <option value="cidade">De Cidade</option>
                        <option value="indicacao">De Indicação</option>
                    </select>
                </div>
                <!-- Percentual por Cirurgia -->
                <div id="percentualPorCirurgia" class="mt-4">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-200">
                            <tr>
                                <th class="px-6 py-3">Tipo de Cirurgia</th>
                                <th class="px-6 py-3 text-right">Total</th>
                                <th class="px-6 py-3 text-right">Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($percentualPorTipo as $tipo)
                                <tr class="bg-white border-b hover:bg-gray-100 transition">
                                    <td class="px-6 py-3">{{ $tipo->descricao }}</td>
                                    <td class="px-6 py-3 text-right">{{ $tipo->total }}</td>
                                    <td class="px-6 py-3 text-right text-blue-600 font-semibold">
                                        {{ number_format($tipo->percentual, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Percentual por Cidade -->
                <div id="percentualPorCidade" class="mt-4 hidden">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-200">
                            <tr>
                                <th class="px-6 py-3">Cidade</th>
                                <th class="px-6 py-3 text-right">Total</th>
                                <th class="px-6 py-3 text-right">Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($percentualPorCidade as $cidade)
                                <tr class="bg-white border-b hover:bg-gray-100 transition">
                                    <td class="px-6 py-3">{{ $cidade->name }}</td>
                                    <td class="px-6 py-3 text-right">{{ $cidade->total }}</td>
                                    <td class="px-6 py-3 text-right text-green-600 font-semibold">
                                        {{ number_format($cidade->percentual, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Percentual por Indicação -->
                <div id="percentualPorIndicacao" class="mt-4 hidden">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-200">
                            <tr>
                                <th class="px-6 py-3">Indicação</th>
                                <th class="px-6 py-3 text-right">Total</th>
                                <th class="px-6 py-3 text-right">Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($percentualPorIndicacao as $indicacao)
                                <tr class="bg-white border-b hover:bg-gray-100 transition">
                                    <td class="px-6 py-3">{{ $indicacao->indication }}</td>
                                    <td class="px-6 py-3 text-right">{{ $indicacao->total }}</td>
                                    <td class="px-6 py-3 text-right text-red-600 font-semibold">
                                        {{ number_format($indicacao->percentual, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="mt-6 bg-gray-100 p-4 rounded-lg shadow-md">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Cirurgias por Cidade</h3>
                <select id="chartTypeSelector"
                    class="bg-white border border-gray-300 text-gray-700 text-sm rounded px-4 py-2">
                    <option value="bar" selected>Gráfico de Barras</option>
                    <option value="pie">Gráfico de Pizza</option>
                </select>
            </div>
            <canvas id="surgeriesByCityChart" class="w-full h-96 mt-4"></canvas>
        </div>

        <!-- Modal de PDF LIstagem  -->
        <div id="pdfModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-3/4 h-3/4 relative">
                <div class="flex justify-between items-center p-4 border-b">
                    <h2 class="text-xl font-bold">Relatório de Cirurgias</h2>
                    <button id="closeModal" class="text-gray-500 hover:text-red-600">
                        <i class="fas fa-times"></i> Fechar
                    </button>
                </div>
                <div class="p-4 h-full">
                    <iframe id="pdfIframe" src="" class="w-full h-full" frameborder="0"></iframe>
                </div>
            </div>
        </div>

        <!-- Janela Flutuante detalhes -->
        <div id="surgeryFlyout" class="fixed bg-gray-800 text-white shadow-lg z-50 rounded-lg hidden"
            style="width: 800px; height: 600px; top: 50px; left: 50px;">
            <!-- Cabeçalho estilo de janela -->
            <div id="flyoutHeader"
                class="cursor-move bg-gray-900 p-4 rounded-t-lg flex justify-between items-center border-b border-gray-700">
                <h2 class="text-lg font-bold text-center flex-1">Detalhes da Cirurgia</h2>
                <!-- Botão de fechar com "X" -->
                <button id="closeFlyout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    X
                </button>
            </div>

            <!-- Conteúdo da Janela -->
            <div id="flyoutContent" class="p-6 overflow-y-auto h-[calc(100%-72px)]">
                <iframe id="Iframepdf" src="" class="w-full h-full" frameborder="0"></iframe>
            </div>


            <!-- Rodapé estilo sistema -->
            <div class="bg-gray-900 p-4 border-t border-gray-700 text-right">
                <button id="openFlyoutPdf" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Imprimir PDF
                </button>
            </div>
        </div>


        



        <script>
            const darkModeToggle = document.getElementById('darkModeToggle');
            darkModeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
            });
        </script>
        {{-- script para impressão da litagem --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const printButton = document.getElementById('printButton');
                const pdfModal = document.getElementById('pdfModal');
                const pdfIframe = document.getElementById('pdfIframe');
                const closeModal = document.getElementById('closeModal');

                // Abrir Modal e carregar PDF
                printButton.addEventListener('click', function(e) {
                    e.preventDefault(); // Evitar o comportamento padrão
                    const surgeonId = "{{ $selectedSurgeonId }}";
                    const startDate = "{{ $startDate }}";
                    const endDate = "{{ $endDate }}";
                    const pdfUrl =
                        `{{ route('dashboard3.pdf') }}?surgeon_id=${surgeonId}&start_date=${startDate}&end_date=${endDate}`;

                    pdfIframe.src = pdfUrl;
                    pdfModal.classList.remove('hidden');
                });

                // Fechar Modal
                closeModal.addEventListener('click', function() {
                    pdfModal.classList.add('hidden');
                    pdfIframe.src = ''; // Limpar o iframe
                });
            });
        </script>
        {{-- FIM script para impressão da litagem --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const openFlyoutPdf = document.getElementById('openFlyoutPdf');
                const flyout = document.getElementById('surgeryFlyout');
                const closeFlyout = document.getElementById('closeFlyout');
                const pdfIframe = document.getElementById('Iframepdf');

                // Abrir Modal e carregar PDF
                openFlyoutPdf.addEventListener('click', function() {
                    const surgeonId = "{{ $selectedSurgeonId }}";
                    const startDate = "{{ $startDate }}";
                    const endDate = "{{ $endDate }}";
                    const pdfUrl =
                        `{{ route('dashboard3.detalhesPdf') }}?surgeon_id=${surgeonId}&start_date=${startDate}&end_date=${endDate}`;

                    pdfIframe.src = pdfUrl;
                    flyout.classList.remove('hidden');
                });

                // Fechar Modal
                closeFlyout.addEventListener('click', function() {
                    flyout.classList.add('hidden');
                    pdfIframe.src = ''; // Limpar o iframe
                });
            });
        </script>


        {{-- //script para o botão detalhes da cirurgia --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const flyout = document.getElementById('surgeryFlyout');
                const closeFlyout = document.getElementById('closeFlyout');
                const flyoutHeader = document.getElementById('flyoutHeader');
                const flyoutContent = document.getElementById('flyoutContent');

                let isDragging = false;
                let offsetX = 0;
                let offsetY = 0;

                // Função para centralizar a janela
                function centerFlyout() {
                    const windowWidth = window.innerWidth;
                    const windowHeight = window.innerHeight;
                    const flyoutWidth = flyout.offsetWidth;
                    const flyoutHeight = flyout.offsetHeight;

                    flyout.style.left = `${(windowWidth - flyoutWidth) / 2}px`;
                    flyout.style.top = `${(windowHeight - flyoutHeight) / 2}px`;
                }

                // Evento para abrir a janela e centralizá-la
                document.querySelectorAll('.openFlyout').forEach(button => {
                    button.addEventListener('click', function() {
                        const surgeryId = this.dataset.id;
                        if (!surgeryId) {
                            alert('ID da cirurgia não encontrado!');
                            return;
                        }

                        const url = `/dashboard3/detalhes/${surgeryId}`;
                        flyoutContent.innerHTML =
                            '<p class="text-center text-gray-400">Carregando...</p>';
                        flyout.style.display = 'block';
                        centerFlyout(); // Centraliza a janela ao abrir

                        fetch(url)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`Erro HTTP! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                flyoutContent.innerHTML = `
                        <div class="grid grid-cols-2 gap-4 text-sm">
                        <!-- Dados do paciente e cirurgia -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-bold mb-2 text-gray-100">Dados do Paciente</h3>
                            <p><strong>Prontuário:</strong> ${data.medical_record || 'N/A'}</p>
                            <p><strong>Paciente:</strong> ${data.name || 'N/A'}</p>
                            <p><strong>Idade:</strong> ${data.age || 'N/A'}</p>
                            <p><strong>Cidade:</strong> ${data.city || 'N/A'}</p>
                            <p><strong>Estado:</strong> ${data.state || 'N/A'}</p>
                        </div>

                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-bold mb-2 text-gray-100">Informações da Cirurgia</h3>
                            <p><strong>Tipo:</strong> ${data.surgery_type || 'N/A'}</p>
                            <p><strong>Data:</strong> ${new Date(data.date).toLocaleDateString('pt-BR') || 'N/A'}</p>
                            <p><strong>Hora:</strong> ${data.time || 'N/A'}</p>
                            <p><strong>Origem:</strong> ${data.origin || 'N/A'}</p>
                        </div>

                        <!-- Equipe médica -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-bold mb-2 text-gray-100">Equipe Médica</h3>
                            <p><strong>Anestesista:</strong> ${data.anestesista || 'N/A'}</p>
                            <p><strong>Cirurgião:</strong> ${data.cirurgiao || 'N/A'}</p>
                            <p><strong>Pediatra:</strong> ${data.pediatra || 'N/A'}</p>
                            <p><strong>Enfermeiro:</strong> ${data.enfermeiro || 'N/A'}</p>
                        </div>

                        <!-- Informações adicionais -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-bold mb-2 text-gray-100">Informações Adicionais</h3>
                            <p><strong>Indicação:</strong> ${data.indication || 'N/A'}</p>
                            <p><strong>Anestesia:</strong> ${data.anesthesia || 'N/A'}</p>
                            <p><strong>Status Social:</strong> ${data.social_status || 'N/A'}</p>
                            <p><strong>APGAR:</strong> ${data.apgar || 'N/A'}</p>
                        </div>

                        <!-- Horários e laqueadura -->
                        <div class="bg-gray-700 p-4 rounded-lg col-span-2">
                            <h3 class="text-lg font-bold mb-2 text-gray-100">Horários e Laqueadura</h3>
                            <p><strong>Hora Final:</strong> ${data.end_time || 'N/A'}</p>
                            <p><strong>Data de Admissão:</strong> ${new Date(data.admission_date).toLocaleDateString('pt-BR') || 'N/A'}</p>
                            <p><strong>Hora de Admissão:</strong> ${data.admission_time || 'N/A'}</p>
                            <p><strong>Laqueadura:</strong> ${data.ligation === 1 ? 'Sim' : 'Não'}</p>
                        </div>
                    </div>
                    `;
                            })
                            .catch(error => {
                                console.error('Erro ao carregar dados:', error);
                                flyoutContent.innerHTML =
                                    '<p class="text-center text-red-500">Erro ao carregar dados.</p>';
                            });
                    });
                });

                // Evento para fechar a janela
                closeFlyout.addEventListener('click', function() {
                    flyout.style.display = 'none';
                });

                // Eventos para arrastar a janela
                flyoutHeader.addEventListener('mousedown', function(e) {
                    isDragging = true;
                    offsetX = e.clientX - flyout.getBoundingClientRect().left;
                    offsetY = e.clientY - flyout.getBoundingClientRect().top;
                    flyoutHeader.style.cursor = 'grabbing';
                });

                document.addEventListener('mousemove', function(e) {
                    if (isDragging) {
                        const x = e.clientX - offsetX;
                        const y = e.clientY - offsetY;

                        flyout.style.left = `${x}px`;
                        flyout.style.top = `${y}px`;
                    }
                });

                document.addEventListener('mouseup', function() {
                    isDragging = false;
                    flyoutHeader.style.cursor = 'grab';
                });
            });
        </script>
        {{-- Script para Alternar entre Cirurgia e Cidade --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selector = document.getElementById('percentualSelector');
                const cirurgiaDiv = document.getElementById('percentualPorCirurgia');
                const cidadeDiv = document.getElementById('percentualPorCidade');
                const indicacaoDiv = document.getElementById('percentualPorIndicacao');

                selector.addEventListener('change', function() {
                    const value = this.value;

                    // Oculta todas as seções
                    cirurgiaDiv.classList.add('hidden');
                    cidadeDiv.classList.add('hidden');
                    indicacaoDiv.classList.add('hidden');

                    // Mostra a seção correspondente
                    if (value === 'cirurgia') {
                        cirurgiaDiv.classList.remove('hidden');
                    } else if (value === 'cidade') {
                        cidadeDiv.classList.remove('hidden');
                    } else if (value === 'indicacao') {
                        indicacaoDiv.classList.remove('hidden');
                    }
                });
            });
        </script>

        {{-- Script para o Gráfico de Cirurgias por Cidade --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const surgeriesData = @json($cirurgiasPorCidade);

                if (!surgeriesData || surgeriesData.length === 0) {
                    document.getElementById('surgeriesByCityChart').parentElement.innerHTML =
                        '<p class="text-center text-gray-500">Nenhum dado disponível para exibir o gráfico.</p>';
                    return;
                }

                const labels = surgeriesData.map(item => item.city);
                const counts = surgeriesData.map(item => item.total);

                const ctx = document.getElementById('surgeriesByCityChart').getContext('2d');
                let chartInstance;

                function generateColors(count) {
                    const colors = [];
                    for (let i = 0; i < count; i++) {
                        colors.push(`hsl(${(i * 360) / count}, 50%, 50%)`);
                    }
                    return colors;
                }

                function createChart(type) {
                    if (chartInstance) chartInstance.destroy();

                    chartInstance = new Chart(ctx, {
                        type: type,
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Cirurgias por Cidade',
                                data: counts,
                                backgroundColor: generateColors(labels.length),
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `Q: ${context.raw}`;
                                        }
                                    }
                                },
                                legend: {
                                    display: type === 'pie',
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top',
                                    formatter: function(value) {
                                        return value; // Apenas exibe a quantidade
                                    },
                                    color: '#000000',
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                }
                            },
                            scales: type === 'bar' ? {
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 50,
                                        minRotation: 45,
                                        color: '#6b7280'
                                    },
                                    grid: {
                                        display: false,
                                    }
                                },
                                y: {
                                    ticks: {
                                        color: '#6b7280'
                                    },
                                    grid: {
                                        color: '#e5e7eb'
                                    }
                                }
                            } : {}
                        },
                        plugins: [ChartDataLabels]
                    });
                }

                createChart('bar');

                document.getElementById('chartTypeSelector').addEventListener('change', function(e) {
                    createChart(e.target.value);
                });
            });
        </script>

</x-app-layout>
