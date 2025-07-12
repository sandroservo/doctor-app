<?php

namespace App\Http\Controllers\Dashboads;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\Surgery;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CirurgiaoDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $surgeonId = $request->query('surgeon_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $patientName = $request->query('patient_name');
        $surgeryType = $request->query('surgery_type'); // Novo filtro


        // Verificar se não há filtros
        if (!$surgeonId && !$startDate && !$endDate && !$patientName && !$surgeryType) {
            return $this->cleanDashboard();
        }

        // Filtros

        $filters = function ($query) use ($surgeonId, $startDate, $endDate, $patientName, $surgeryType) {
            $query->when($surgeonId, fn($q) => $q->where('surgeries.cirurgiao_id', $surgeonId)) // Use 'surgeries.' explicitamente
                ->when($startDate, fn($q) => $q->where('surgeries.date', '>=', $startDate)) // Use 'surgeries.'
                ->when($endDate, fn($q) => $q->where('surgeries.date', '<=', $endDate))
                ->when($patientName, fn($q) => $q->where('surgeries.name', 'like', "%$patientName%")) // Corrija se 'name' for de outra tabela
                ->when($surgeryType, fn($q) => $q->whereHas('surgery_type', fn($subquery) => $subquery->where('tipo', 'like', "%$surgeryType%")));
        };

        // Contagens e Totais
        $totalSurgeries = Surgery::where($filters)->count();
        $totalObstetricSurgeries = Surgery::where($filters)
            ->whereHas('surgery_type', fn($q) => $q->where('tipo', 'like', '%Obstetrica%'))
            ->count();
        $totalcirurgiasGinecologicas = Surgery::where($filters)
            ->whereHas('surgery_type', fn($q) => $q->where('tipo', 'like', '%Ginecologica%'))
            ->count();
        $totalcirurgiasPediátricas = Surgery::where($filters)
            ->whereHas('surgery_type', fn($q) => $q->where('tipo', 'like', '%Pediatrica%'))
            ->count();
        $apgarAbaixoDeSete = Surgery::where($filters)->where('apgar', '<', 7)->count();

        // Cirurgias por período
        $quantidadeCirurgiasManha = Surgery::where($filters)->whereBetween('time', ['07:00:00', '11:59:59'])->count();
        $quantidadeCirurgiasTarde = Surgery::where($filters)->whereBetween('time', ['12:00:00', '18:59:59'])->count();
        $quantidadeCirurgiasNoite = Surgery::where($filters)->whereBetween('time', ['19:00:00', '23:59:59'])->count();
        $quantidadeCirurgiasMadrugda = Surgery::where($filters)->whereBetween('time', ['00:00:00', '06:59:59'])->count();

        // Cirurgias paginadas
        $surgeries = Surgery::with(['anestesista', 'cirurgiao', 'pediatra', 'enfermeiro'])
            ->where($filters)
            ->orderBy('id', 'desc')
            ->paginate(10);

        // Cidade mais atendida
        $cidadeMaisAtendida = DB::table('surgeries')
            ->join('cities', 'surgeries.citie_id', '=', 'cities.id') // Relaciona com a tabela de cidades
            ->select('cities.name as city', DB::raw('COUNT(surgeries.id) as total')) // Conta as cirurgias por cidade
            ->when($filters, function ($query) use ($filters) {
                $query->where($filters); // Aplica os filtros dinâmicos (como cirurgião, data, etc.)
            })
            ->groupBy('cities.name')
            ->orderByDesc('total')
            ->first();


        // Cirurgia mais realizada
        $cirurgiaMaisRealizada = Surgery::where($filters)
            ->join('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id') // Relacione com a tabela de tipos de cirurgia
            ->select('surgery_types.descricao', DB::raw('COUNT(*) as total'))
            ->groupBy('surgery_types.descricao')
            ->orderByDesc('total')
            ->first();
        // Percentual por tipo de cirurgia
        $percentualPorTipo = Surgery::where($filters)
            ->join('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id') // Relacione com a tabela de tipos de cirurgia
            ->select('surgery_types.descricao', DB::raw('COUNT(*) as total'), DB::raw('(COUNT(*) / SUM(COUNT(*)) OVER()) * 100 as percentual'))
            ->groupBy('surgery_types.descricao')
            ->orderByDesc('total')
            ->get();
        // Percentual por cidade
        $percentualPorCidade = Surgery::where($filters)
            ->join('cities', 'surgeries.citie_id', '=', 'cities.id')
            ->select('cities.name', DB::raw('COUNT(*) as total'), DB::raw('(COUNT(*) / SUM(COUNT(*)) OVER()) * 100 as percentual'))
            ->groupBy('cities.name')
            ->orderByDesc('total')
            ->get();
        // Percentual por indicação
        $percentualPorIndicacao = Surgery::join('indications', 'surgeries.indication_id', '=', 'indications.id')
            ->select('indications.descricao as indication', DB::raw('COUNT(surgeries.id) as total'))
            ->groupBy('indications.descricao')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) use ($filters) {
                $totalGeral = Surgery::where($filters)->count(); // Total geral de cirurgias considerando os filtros
                $item->percentual = $totalGeral > 0 ? ($item->total / $totalGeral) * 100 : 0;
                return $item;
            });
        //Obter as cirurgias por cidade
        //     $cirurgiasPorCidade = DB::table('surgeries')
        //         ->join('cities', 'surgeries.citie_id', '=', 'cities.id')
        //         ->selectRaw('
        //     cities.name as city, 
        //     COUNT(*) as total, 
        //     (COUNT(*) * 100.0 / (SELECT COUNT(*) FROM surgeries WHERE cirurgiao_id = ?)) as percentual
        // ', [$surgeonId]) // Substitua $surgeonId pelo ID do cirurgião selecionado
        //         ->where('surgeries.cirurgiao_id', $surgeonId) // Filtra pelo cirurgião
        //         ->groupBy('cities.name')
        //         ->orderByDesc('total') // Ordena pela quantidade de cirurgias
        //         ->get();

        $cirurgiasPorCidade = DB::table('surgeries')
            ->join('cities', 'surgeries.citie_id', '=', 'cities.id')
            ->selectRaw('
        cities.name as city, 
        COUNT(*) as total, 
        (COUNT(*) * 100.0 / (SELECT COUNT(*) FROM surgeries WHERE cirurgiao_id = ? AND date BETWEEN ? AND ?)) as percentual
    ', [$surgeonId, $startDate, $endDate]) // Parâmetros dinâmicos
            ->where('surgeries.cirurgiao_id', $surgeonId)
            ->whereBetween('surgeries.date', [$startDate, $endDate]) // Filtra pelo período
            ->groupBy('cities.name')
            ->orderByDesc('total')
            ->get();

        return view('dashboard3', [
            'totalSurgeries' => $totalSurgeries,
            'totalObstetricSurgeries' => $totalObstetricSurgeries,
            'totalcirurgiasGinecologicas' => $totalcirurgiasGinecologicas,
            'totalcirurgiasPediátricas' => $totalcirurgiasPediátricas,
            'apgarAbaixoDeSete' => $apgarAbaixoDeSete,
            'quantidadeCirurgiasManha' => $quantidadeCirurgiasManha,
            'quantidadeCirurgiasTarde' => $quantidadeCirurgiasTarde,
            'quantidadeCirurgiasNoite' => $quantidadeCirurgiasNoite,
            'quantidadeCirurgiasMadrugda' => $quantidadeCirurgiasMadrugda,
            'surgeries' => $surgeries,
            'professionals' => Professional::all(),
            'selectedSurgeonId' => $surgeonId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'patientName' => $patientName,
            'cidadeMaisAtendida' => $cidadeMaisAtendida,
            'cirurgiaMaisRealizada' => $cirurgiaMaisRealizada,
            'percentualPorTipo' => $percentualPorTipo,
            'percentualPorCidade' => $percentualPorCidade,
            'cirurgiasPorCidade' => $cirurgiasPorCidade,
            'percentualPorIndicacao' => $percentualPorIndicacao
        ]);
    }

    /**
     * Retorna a dashboard limpa (sem dados).
     */
    private function cleanDashboard()
    {
        return view('dashboard3', [
            'surgeries' => Surgery::query()->orderBy('id', 'desc')->paginate(10),
            'totalSurgeries' => 0,
            'totalObstetricSurgeries' => 0,
            'totalcirurgiasGinecologicas' => 0,
            'totalcirurgiasPediátricas' => 0,
            'apgarAbaixoDeSete' => 0,
            'quantidadeCirurgiasManha' => 0,
            'quantidadeCirurgiasTarde' => 0,
            'quantidadeCirurgiasNoite' => 0,
            'quantidadeCirurgiasMadrugda' => 0,
            'professionals' => Professional::all(),
            'selectedSurgeonId' => null,
            'startDate' => null,
            'endDate' => null,
            'patientName' => null,
            'cidadeMaisAtendida' => null,
            'cirurgiaMaisRealizada' => null,
            'percentualPorTipo' => null,
            'percentualPorCidade' => null,
            'cirurgiasPorCidade' => null,
            'percentualPorIndicacao' => null
        ]);
    }

    /**
     * Show the details of a specific surgery.
     */
    public function show($id)
    {
        $surgery = Surgery::with(['anestesista', 'cirurgiao', 'pediatra', 'enfermeiro'])->findOrFail($id);

        return response()->json([
            'medical_record' => $surgery->medical_record,
            'name' => $surgery->name,
            'date' => $surgery->date,
            'time' => $surgery->time,
            'age' => $surgery->age,
            'city' => $surgery->city ? $surgery->city->name : 'N/A',
            'state' => $surgery->state ? $surgery->state->name : 'N/A',
            'origin' => $surgery->origin_department,
            'anestesista' => $surgery->anestesista ? $surgery->anestesista->name : 'N/A',
            'cirurgiao' => $surgery->cirurgiao ? $surgery->cirurgiao->name : 'N/A',
            'pediatra' => $surgery->pediatra ? $surgery->pediatra->name : 'N/A',
            'enfermeiro' => $surgery->enfermeiro ? $surgery->enfermeiro->name : 'N/A',
            'indication' => $surgery->indication ? $surgery->indication->descricao : 'N/A',
            'anesthesia' => $surgery->anesthesia,
            'surgery_type' => $surgery->surgery_type ? $surgery->surgery_type->descricao : 'N/A',
            'end_time' => $surgery->end_time,
            'admission_date' => $surgery->admission_date,
            'admission_time' => $surgery->admission_time,
            'social_status' => $surgery->social_status,
            'apgar' => $surgery->apgar,
        ]);
    }


    public function detalhesPdf(Request $request)
    {
        $surgeonId = $request->query('surgeon_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Obter o nome do cirurgião
        $surgeon = Professional::find($surgeonId);

        // Filtrar cirurgias
        $surgeries = Surgery::with(['city', 'state', 'anestesista', 'cirurgiao', 'pediatra', 'enfermeiro'])
            ->where('cirurgiao_id', $surgeonId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        // Gerar o PDF
        $pdf = Pdf::loadView('pdfdashboard.detalhespdf', compact('surgeries', 'surgeon', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="relatorio-cirurgias.pdf"');
    }
}
