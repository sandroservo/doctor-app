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
        $surgeryType = $request->query('surgery_type');
        $timePeriod = $request->query('time_period');
        $timeStart = $request->query('time_start'); // Horário de início
        $timeEnd = $request->query('time_end'); // Horário de término


        // Verificar se não há filtros
        if (!$surgeonId && !$startDate && !$endDate && !$patientName && !$surgeryType) {
            return $this->cleanDashboard();
        }


        $filters = function ($query) use ($surgeonId, $startDate, $endDate, $patientName, $surgeryType) {
            $query->when($surgeonId, fn($q) => $q->where('cirurgiao_id', $surgeonId))
                ->when($startDate, fn($q) => $q->where('date', '>=', $startDate))
                ->when($endDate, fn($q) => $q->where('date', '<=', $endDate))
                ->when($patientName, fn($q) => $q->where('surgeries.name', 'like', "%$patientName%"))
                ->when($surgeryType, fn($q) => $q->whereHas('surgery_type', fn($sq) => $sq->where('descricao', $surgeryType)));
        };

        // Aplicar filtro de tipo de cirurgia
        if ($surgeryType) {
            $filters = function ($query) use ($filters, $surgeryType) {
                $query->whereHas('surgery_type', fn($q) => $q->where('tipo', 'like', "%$surgeryType%"))
                    ->where($filters);
            };
        }

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
        $apgarAbaixoDeSete = Surgery::where($filters)
            ->when($request->has('city'), function ($query) use ($request) {
                $query->whereHas('city', function ($q) use ($request) {
                    $q->where('cities.name', 'like', "%{$request->query('city')}%");
                });
            })
            ->where('apgar', '<', 7)
            ->count();



        // Cirurgias por período
        $totalSurgeries = Surgery::where($filters)->count();
        $quantidadeCirurgiasManha = Surgery::where($filters)->whereBetween('time', ['07:00:00', '11:59:59'])->count();
        $quantidadeCirurgiasTarde = Surgery::where($filters)->whereBetween('time', ['12:00:00', '18:59:59'])->count();
        $quantidadeCirurgiasNoite = Surgery::where($filters)->whereBetween('time', ['19:00:00', '23:59:59'])->count();
        $quantidadeCirurgiasMadrugda = Surgery::where($filters)->whereBetween('time', ['00:00:00', '06:59:59'])->count();


        // Filtro adicional para o período de manhã
        if ($timePeriod === 'manha') {
            $filters = function ($query) use ($filters) {
                $filters($query);
                $query->whereBetween('time', ['07:00:00', '11:59:59']);
            };
        }

        // Filtro adicional para o período de tarde
        if ($timePeriod === 'tarde') {
            $filters = function ($query) use ($filters) {
                $filters($query);
                $query->whereBetween('time', ['12:00:00', '18:59:59']);
            };
        }
        // Filtro adicional para o período de noite
        if ($timePeriod === 'noite') {
            $filters = function ($query) use ($filters) {
                $filters($query);
                $query->whereBetween('time', ['19:00:00', '23:59:59']);
            };
        }

        // Filtro adicional para o período de madrugada
        if ($timePeriod === 'madrugada') {
            $filters = function ($query) use ($filters) {
                $filters($query);
                $query->whereBetween('time', ['00:00:00', '06:59:59']);
            };
        }

        // Aplicar filtro de cidade corretamente
        if ($request->has('city')) {
            $filters = function ($query) use ($filters, $request) {
                $filters($query);
                $query->where('cities.name', 'like', "%{$request->query('city')}%");
            };
        }


        if ($request->has('surgery_type')) {
            $filters = function ($query) use ($filters, $request) {
                $filters($query);
                $query->whereHas('surgery_type', fn($q) => $q->where('descricao', $request->query('surgery_type')));
            };
        }


        // Filtro para cards apgar_filter
        if ($request->query('apgar_filter')) {
            $filters = function ($query) use ($surgeonId, $startDate, $endDate) {
                $query->when($surgeonId, fn($q) => $q->where('cirurgiao_id', $surgeonId))
                    ->when($startDate, fn($q) => $q->where('date', '>=', $startDate))
                    ->when($endDate, fn($q) => $q->where('date', '<=', $endDate))
                    ->where('apgar', '<', 7);
            };

            $surgeries = Surgery::with(['anestesista', 'cirurgiao', 'pediatra', 'enfermeiro'])
                ->where($filters)
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        // Filtra cirurgias mais realizadas

        // Aplicar filtros
        $filters = function ($query) use ($filters, $request, $startDate, $endDate) {
            $filters($query);
            $query->when($startDate, fn($q) => $q->where('date', '>=', $startDate))
                ->when($endDate, fn($q) => $q->where('date', '<=', $endDate))
                ->when($request->has('surgery_type'), fn($q) => $q->whereHas('surgery_type', fn($q) => $q->where('descricao', $request->query('surgery_type'))));
        };




        // Cirurgias paginadas com filtro por cidade
        $surgeries = Surgery::with(['anestesista', 'cirurgiao', 'pediatra', 'enfermeiro', 'city'])
            ->where($filters)
            ->when($timeStart && $timeEnd, fn($q) => $q->whereBetween('time', [$timeStart, $timeEnd]))
            ->when($request->has('city'), function ($query) use ($request) {
                $query->join('cities', 'surgeries.citie_id', '=', 'cities.id')
                    ->where('cities.name', 'like', "%{$request->query('city')}%");
            })
            ->orderBy('surgeries.id', 'desc') // Especificar a tabela para evitar ambiguidade
            ->paginate(10);


        // Ajuste completo na busca por cidade mais atendida
        $cidadeMaisAtendida = Surgery::select('cities.name as city', DB::raw('COUNT(surgeries.id) as total'))
            ->join('cities', 'cities.id', '=', 'surgeries.citie_id')
            ->where($filters)
            ->groupBy('cities.name')
            ->orderByDesc('total')
            ->first() ?? (object) ['city' => 'N/A', 'total' => 0];




        // Encontrar a cirurgia mais realizada
        $cirurgiaMaisRealizada = DB::table('surgeries')
            ->join('surgery_types', 'surgery_types.id', '=', 'surgeries.surgery_id')
            ->select('surgery_types.descricao', DB::raw('COUNT(surgeries.id) as total'))
            ->whereNotNull('surgery_types.descricao') // Garante que o tipo de cirurgia não seja nulo
            ->when($surgeonId, function ($query) use ($surgeonId) {
                $query->where('surgeries.cirurgiao_id', $surgeonId);
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('surgeries.date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where('surgeries.date', '<=', $endDate);
            })
            ->groupBy('surgery_types.descricao')
            ->orderByDesc('total')
            ->first();
        //dd($cirurgiaMaisRealizada);




        // Percentual por tipo de cirurgia
        $percentualPorTipo = Surgery::where($filters)
            ->join('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id')
            ->join('cities', 'surgeries.citie_id', '=', 'cities.id')
            ->select('surgery_types.descricao', DB::raw('COUNT(*) as total'), DB::raw('(COUNT(*) / SUM(COUNT(*)) OVER()) * 100 as percentual'))
            ->where('cities.name', 'like', "%{$request->query('city')}%")
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
            ->join('cities', 'cities.id', '=', 'surgeries.citie_id') // Adiciona o join com cities
            ->select('indications.descricao as indication', DB::raw('COUNT(surgeries.id) as total'))
            ->where('cities.name', 'like', "%{$request->query('city')}%") // Aplica o filtro por cidade
            ->groupBy('indications.descricao')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) use ($request) {
                $totalGeral = Surgery::join('cities', 'cities.id', '=', 'surgeries.citie_id')
                    ->where('cities.name', 'like', "%{$request->query('city')}%")
                    ->count();

                $item->percentual = $totalGeral > 0 ? ($item->total / $totalGeral) * 100 : 0;
                return $item;
            });

        // Percentual por cirurgião
        $cirurgiasPorCidade = DB::table('surgeries')
            ->join('cities', 'surgeries.citie_id', '=', 'cities.id')
            ->selectRaw('cities.name as city, COUNT(*) as total, (COUNT(*) * 100.0 / (SELECT COUNT(*) FROM surgeries WHERE cirurgiao_id = ? AND date BETWEEN ? AND ?)) as percentual', [$surgeonId, $startDate, $endDate])
            ->where('surgeries.cirurgiao_id', $surgeonId)
            ->whereBetween('surgeries.date', [$startDate, $endDate])
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
            'professionals' => Professional::where('specialty', 'C')->get(),
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
            'professionals' => Professional::where('specialty', 'C')->get(),
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
            'city' => optional($surgery->city)->name ?? 'N/A',
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
