<!-- resources/views/reports/pdf.blade.php -->
@php
    use Carbon\Carbon;
@endphp

<h2>Relatório de Cirurgias - Tipo: {{ $surgeries->first()->surgery_type->descricao ?? 'N/A' }} - Mês: {{ Carbon::createFromDate(null, $month, 1)->locale('pt_BR')->isoFormat('MMMM') }}</h2>
<table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Nome do Paciente</th>
            <th>Idade</th>
            <th>Prontuário</th>
            <th>Cirurgião</th>
            <th>Enfermeiro</th>
            <th>Anestesia</th>
            <th>Anestesista</th>
            <th>APGAR</th>
            <th>Status Social</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($surgeries as $surgery)
            <tr>
                <td>{{ \Carbon\Carbon::parse($surgery->date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($surgery->time)->format('H:i') }}</td>
                <td>{{ $surgery->name }}</td>
                <td>{{ $surgery->age }}</td>
                <td>{{ $surgery->medical_record }}</td>
                <td>{{ $surgery->cirurgiao->name ?? 'N/A' }}</td>
                <td>{{ $surgery->enfermeiro->name ?? 'N/A' }}</td>
                <td>{{ $surgery->anestesista->name ?? 'N/A' }}</td>
                <td>{{ $surgery->anesthesia }}</td>
                <td>{{ $surgery->apgar }}</td>
                <td>{{ $surgery->social_status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
