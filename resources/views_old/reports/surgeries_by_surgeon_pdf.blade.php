<!-- resources/views/reports/surgeries_by_surgeon_pdf.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Cirurgias do Mês por Cirurgião</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        h2, h3 { text-align: center; }
    </style>
</head>
<body>
    <h2>Relatório de Cirurgias do Mês por Cirurgião</h2>

    @foreach ($surgeries as $surgeon => $surgeriesBySurgeon)
        <h3>{{ $surgeon->name ?? 'Cirurgião Não Informado' }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Tipo de Cirurgia</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($surgeriesBySurgeon as $surgery)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($surgery->time)->format('H:i') }}</td>
                        <td>{{ $surgery->name }}</td>
                        <td>{{ $surgery->surgery_type->descricao ?? 'Não especificado' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
