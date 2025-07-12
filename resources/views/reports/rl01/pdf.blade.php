<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Cirurgias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Relatório de Cirurgias</h1>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo de Cirurgia</th>
                <th>Data</th>
                <th>Horário</th>
                <th>Cirurgião</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surgeries as $surgery)
                <tr>
                    <td>{{ $surgery->name }}</td>
                    <td>{{ $surgery->surgery_type->descricao }}</td>
                    <td>{{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}</td>
                    <td>{{ $surgery->time }}</td>
                    <td>{{ $surgery->cirurgiao->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
