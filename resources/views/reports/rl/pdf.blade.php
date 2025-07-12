<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Cirurgias</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Relatório de Cirurgias por Cirurgião</h2>
    <table>
        <thead>
            <tr>
                <th>Nome do Cirurgião</th>
                <th>Tipo de Cirurgia</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Quantidade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surgeriesReport as $report)
                <tr>
                    <td>{{ $report['surgeon_name'] }}</td>
                    <td>{{ $report['patient_name'] }}</td>
                    <td>{{ $report['surgery_type'] }}</td>
                    <td>{{ $report['surgery_date'] }}</td>
                    <td>{{ $report['surgery_time'] }}</td>
                    <td>{{ $report['surgery_count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
