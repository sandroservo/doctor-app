<!-- resources/views/reports/surgery_percentage_by_city_pdf.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Porcentagem de Cirurgias por Cidade</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Relatório de Porcentagem de Cirurgias por Cidade</h2>
    <p>Total de Cirurgias: {{ $totalSurgeries }}</p>

    <table>
        <thead>
            <tr>
                <th>Cidade</th>
                <th>Quantidade de Cirurgias</th>
                <th>Porcentagem</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['city'] }}</td>
                    <td>{{ $item['count'] }}</td>
                    <td>{{ number_format($item['percentage'], 2) }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th>{{ $totalSurgeries }}</th>
                <th>100%</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
