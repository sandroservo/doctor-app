<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Cirurgias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        .title {
            text-align: center;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Para que todas as colunas tenham largura fixa */
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #f0f0f0;
            font-size: 9px;
        }
        td {
            font-size: 9px;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="title">Relatório de Cirurgias</div>
    <div class="subtitle">
        <strong>Cirurgião:</strong> {{ $surgeon->name ?? 'Não especificado' }}<br>
        <strong>Período:</strong> 
{{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') ?? 'Início' }} 
a 
{{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') ?? 'Fim' }}

    </div>
    <table>
        <thead>
            <tr>
                <th style="white-space: nowrap; text-align: center;">Prontuário</th>
                <th style="white-space: nowrap; text-align: center;">Data</th>
                <th style="white-space: nowrap; text-align: center;">Hora</th>
                <th style="text-align: center;">Paciente</th>
                <th style="white-space: nowrap; text-align: center;">Idade</th>
                <th style="text-align: center;">Cidade</th>
                <th style="white-space: nowrap; text-align: center;">Estado</th>
                <th style="white-space: nowrap; text-align: center;">Anestesista</th>
                <th style="white-space: nowrap; text-align: center;">Cirurgião</th>
                <th style="white-space: nowrap; text-align: center;">Pediatra</th>
                <th style="white-space: nowrap; text-align: center;">Enfermeiro</th>
                <th style="white-space: nowrap; text-align: center;">Indicação</th>
                <th style="text-align: center;">Tipo</th>
                <th style="white-space: nowrap; text-align: center;">AdmData</th>
                <th style="white-space: nowrap; text-align: center;">AdmHora</th>
                <th style="white-space: nowrap; text-align: center;">Origem</th>
                <th style="white-space: nowrap; text-align: center;">Anestesia</th>
                <th style="white-space: nowrap; text-align: center;">APGAR</th>
                <th style="white-space: nowrap; text-align: center;">Hora Final</th>
                <th style="white-space: nowrap; text-align: center;">Ligar</th>
                <th style="text-align: center;">Social</th>
            </tr>
            
            
            
        </thead>
        <tbody>
            @foreach ($surgeries as $surgery)
            <tr>
                <td style="white-space: nowrap;">{{ $surgery->medical_record }}</td>
                <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}</td>
                <td >{{ $surgery->time }}</td>
                <td >{{ $surgery->name }}</td>
                <td style="white-space: nowrap;">{{ $surgery->age }}</td>
                <td >{{ $surgery->city->name ?? 'N/A' }}</td>
                <td style="white-space: nowrap;">{{ $surgery->state->name ?? 'N/A' }}</td>
                <td style="white-space: nowrap;">{{ $surgery->anestesista->name ?? 'N/A' }}</td>
                <td>{{ $surgery->cirurgiao->name ?? 'N/A' }}</td>
                <td>{{ $surgery->pediatra->name ?? 'N/A' }}</td>
                <td style="white-space: nowrap;">{{ $surgery->enfermeiro->name ?? 'N/A' }}</td>
                <td>{{ $surgery->indication->descricao ?? 'N/A' }}</td>
                <td>{{ $surgery->surgery_type->descricao ?? 'N/A' }}</td>
                <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($surgery->admission_date)->format('d/m/Y') }}</td>
                <td style="white-space: nowrap;">{{ $surgery->admission_time }}</td>
                <td style="white-space: nowrap;">{{ $surgery->origin_department }}</td>
                <td style="white-space: nowrap;">{{ $surgery->anesthesia }}</td>
                <td style="white-space: nowrap;">{{ $surgery->apgar }}</td>
                <td style="white-space: nowrap;">{{ $surgery->end_time }}</td>
                <td style="white-space: nowrap;">{{ $surgery->ligation ? 'Sim' : 'Não' }}</td>
                <td >{{ $surgery->social_status }}</td>
            </tr>
            
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Relatório gerado em {{ now()->format('d/m/Y H:i:s') }}
    </div>
 
</body>
</html>
