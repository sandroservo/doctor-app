<!-- resources/views/reports/total_surgeries_pdf.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Geral de Cirurgias do Mês</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Relatório Geral de Cirurgias do Mês</h2>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Idade</th>
                <th>Cirurgião</th>
                <th>Enfermeiro</th>
                <th>Anestesista</th>
                <th>Pediatra</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surgeries as $surgery)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($surgery->time)->format('H:i') }}</td>
                    <td>{{ $surgery->name }}</td>
                    <td>{{ $surgery->age }}</td>
                    <td>{{ $surgery->cirurgiao->name ?? 'Não informado' }}</td>
                    <td>{{ $surgery->enfermeiro->name ?? 'Não informado' }}</td>
                    <td>{{ $surgery->anestesista->name ?? 'Não informado' }}</td>
                    <td>{{ $surgery->pediatra->name ?? 'Não informado' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
