<x-app-layout>
    <div class="container mx-auto p-6 bg-gray-900 text-gray-200">
        <h1 class="text-2xl font-bold mb-4 text-center">Relatório de Cirurgias</h1>

        <!-- Filtros -->
        <div class="bg-gray-800 shadow-md rounded p-4 mb-6">
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Cirurgião -->
                <div>
                    <label for="surgeon" class="block font-semibold text-gray-400">Cirurgião</label>
                    <select id="surgeon" name="surgeon" class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded">
                        <option value="">Selecione</option>
                        @foreach ($surgeons as $surgeon)
                            <option value="{{ $surgeon->id }}">{{ $surgeon->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de cirurgia -->
                <div>
                    <label for="type" class="block font-semibold text-gray-400">Tipo de Cirurgia</label>
                    <select id="type" name="type"
                        class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded">
                        <option value="">Selecione</option>
                        @foreach ($surgeryTypes as $type)
                            <option value="{{ $type->tipo }}">{{ $type->tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Data inicial -->
                <div>
                    <label for="start_date" class="block font-semibold text-gray-400">Data Inicial</label>
                    <input type="date" id="start_date" name="start_date"
                        class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded">
                </div>

                <!-- Data final -->
                <div>
                    <label for="end_date" class="block font-semibold text-gray-400">Data Final</label>
                    <input type="date" id="end_date" name="end_date"
                        class="w-full border-gray-600 bg-gray-700 text-gray-200 rounded">
                </div>
            </form>
            <button id="filterButton" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Filtrar</button>
        </div>

        <!-- Resultados -->
        <div>
            <table class="table-auto w-full bg-gray-800 shadow-md rounded text-center">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="px-4 py-2 text-gray-400">Médico</th>
                        <th class="px-4 py-2 text-gray-400">Paciente</th>
                        <th class="px-4 py-2 text-gray-400">Tipo de Cirurgia</th>
                        <th class="px-4 py-2 text-gray-400">Data</th>
                        <th class="px-4 py-2 text-gray-400">Ações</th>
                    </tr>
                </thead>
                <tbody id="reportResults" class="divide-y divide-gray-600">
                    <!-- Os resultados serão carregados aqui dinamicamente -->
                </tbody>
            </table>
            <div id="pagination" class="mt-4 flex justify-center items-center space-x-2">
                <!-- Paginação será carregada aqui dinamicamente -->
            </div>
        </div>

        <!-- Modal -->
        <div id="detailsModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
            <div class="bg-gray-900 rounded-lg shadow-lg p-6 w-3/4 max-w-4xl text-gray-200">
                <h2 class="text-2xl font-bold text-center mb-4 border-b-2 border-gray-700 pb-2">Detalhes da Cirurgia
                </h2>
                <div id="detailsContent" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Os detalhes serão carregados dinamicamente aqui -->
                </div>
                <div class="flex justify-end mt-6">
                    <button id="closeModal" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                        Fechar
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        let currentPage = 1;

        document.getElementById('filterButton').addEventListener('click', function() {
            loadResults(1);
        });

        function loadResults(page) {
            currentPage = page;
            const formData = new FormData(document.getElementById('filterForm'));
            formData.append('page', page);

            fetch('{{ route('reports.surgery-rl06.filter') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const results = data.data;
                    const tableBody = document.getElementById('reportResults');
                    tableBody.innerHTML = '';
                    results.forEach((item, index) => {
                        const rowClass = index % 2 === 0 ? 'bg-gray-700' : 'bg-gray-600';
                        const formattedDate = item.date ?
                            new Intl.DateTimeFormat('pt-BR', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                            }).format(new Date(item.date)) :
                            'Não informado';
                        const row = `<tr class="${rowClass} text-gray-200 text-center">
                        <td class="px-4 py-2">${item.cirurgiao_name}</td>
                        <td class="px-4 py-2">${item.name_paciente}</td>
                        <td class="px-4 py-2">${item.descricao_cirurgia}</td>
                        <td class="px-4 py-2">${formattedDate}</td>
                        <td class="px-4 py-2">
                            <button onclick="showDetails(${item.id})" class="bg-blue-500 text-white px-2 py-1 rounded">Detalhes</button>
                        </td>
                    </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });

                    const pagination = document.getElementById('pagination');
                    pagination.innerHTML = '';
                    for (let i = 1; i <= data.last_page; i++) {
                        const pageButton =
                            `<button onclick="loadResults(${i})" class="px-3 py-1 mx-1 ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-600 text-gray-200'} rounded">${i}</button>`;
                        pagination.insertAdjacentHTML('beforeend', pageButton);
                    }
                });
        }

        function formatDate(dateString) {
            if (!dateString) return 'Não informado';
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            };
            return new Intl.DateTimeFormat('pt-BR', options).format(new Date(dateString));
        }

        function formatLigation(ligationValue) {
            return ligationValue === 1 ? 'Sim' : 'Não';
        }

        function formatAnesthesia(anesthesiaCode) {
        const anesthesiaMap = {
            'RA': 'Raque-anestesia',
            'S': 'Sedação',
            'GE': 'Geral Entubada',
            'I': 'Inalatória',
            'L': 'Local'
        };
        return anesthesiaMap[anesthesiaCode] || 'Não informado';
    }

        function showDetails(id) {
            fetch(`/reports/surgeries/rl06/details/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.data) {
                        const modal = document.getElementById('detailsModal');
                        const content = document.getElementById('detailsContent');
                        const details = data.data;

                        content.innerHTML = `
                    <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-semibold border-b-2 border-gray-700 pb-2">Informações Gerais</h3>
                        </div>
                        <div>
                            <p><strong>Data:</strong> ${formatDate(details.date) || 'Não informado'}</p>
                            <p><strong>Hora:</strong> ${details.time || 'Não informado'}</p>
                            <p><strong>Nome do Paciente:</strong> ${details.paciente_name || 'Não informado'}</p>
                            <p><strong>Idade:</strong> ${details.age || 'Não informado'}</p>
                        </div>
                        <div>
                            <p><strong>Estado:</strong> ${details.state_name || 'Não informado'}</p>
                            <p><strong>Cidade:</strong> ${details.city_name || 'Não informado'}</p>
                            <p><strong>Prontuário:</strong> ${details.prontuario || 'Não informado'}</p>
                            <p><strong>Origem do Setor:</strong> ${details.origem_setor || 'Não informado'}</p>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-semibold border-b-2 border-gray-700 pb-2">Informações Médicas</h3>
                        </div>
                        <div>
                            <p><strong>Indicação:</strong> ${details.indication || 'Não informado'}</p>
                            <p><strong>Anestesia:</strong> ${formatAnesthesia(details.anesthesia) || 'Não informado'}</p>
                            <p><strong>Anestesista:</strong> ${details.anestesista_name || 'Não informado'}</p>
                            <p><strong>Pediatra:</strong> ${details.pediatra_name || 'Não informado'}</p>
                        </div>
                        <div>
                            <p><strong>Enfermeiro:</strong> ${details.enfermeiro_name || 'Não informado'}</p>
                            <p><strong>Data de Admissão:</strong> ${formatDate(details.data_admissao) || 'Não informado'}</p>
                            <p><strong>Hora de Admissão:</strong> ${details.hora_admissao || 'Não informado'}</p>
                            <p><strong>Hora de Término:</strong> ${details.hora_termino || 'Não informado'}</p>
                        </div>
                        <div>
                            <p><strong>APGAR:</strong> ${details.apgar || 'Não informado'}</p>
                            <p><strong>Ligadura:</strong> ${formatLigation(details.ligation) || 'Não informado'}</p>
                            <p><strong>Status Social:</strong> ${details.social_status || 'Não informado'}</p>
                            <p><strong>Cirurgia:</strong> ${details.tipo_cirurgia || 'Não informado'}</p>
                        </div>
                        <div>
                            <p><strong>Cirurgião:</strong> ${details.cirurgiao_name || 'Não informado'}</p>
                        </div>
                `;
                        modal.classList.remove('hidden');
                    } else {
                        alert('Detalhes não encontrados.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar detalhes:', error.message);
                    alert('Erro ao carregar os detalhes: ' + error.message);
                });
        }





        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('detailsModal').classList.add('hidden');
        });
    </script>
</x-app-layout>
