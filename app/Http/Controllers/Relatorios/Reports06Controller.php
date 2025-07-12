<?php

namespace App\Http\Controllers\Relatorios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reports06Controller extends Controller
{
    /**
     * Exibe a página inicial do relatório.
     */
    public function index()
    {
        $surgeons = DB::table('professionals')
            ->join('surgeries', 'professionals.id', '=', 'surgeries.cirurgiao_id')
            ->select('professionals.id', 'professionals.name')
            ->distinct()
            ->get();

        $surgeryTypes = DB::table('surgery_types')
            ->select('tipo') // Seleciona apenas o campo necessário
            ->groupBy('tipo') // Garante que não haverá duplicatas
            ->get();

        return view('reports.rl06.index', compact('surgeons', 'surgeryTypes'));
    }

    /**
     * Filtra os dados com base nos parâmetros fornecidos.
     */
    public function filter(Request $request)
    {
        try {
            $query = DB::table('surgeries')
                ->join('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id')
                ->join('professionals as cirurgiao', 'surgeries.cirurgiao_id', '=', 'cirurgiao.id')
                ->select(
                    'surgeries.id',
                    'surgeries.date',
                    'cirurgiao.name as cirurgiao_name',
                    'surgeries.name as name_paciente',
                    'surgery_types.descricao as descricao_cirurgia',
                    'surgery_types.tipo as tipo_cirurgia'
                );

            if ($request->filled('surgeon')) {
                $query->where('cirurgiao.id', $request->input('surgeon'));
            }

            if ($request->filled('type')) {
                $query->where('surgery_types.tipo', $request->input('type'));
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('surgeries.date', [
                    $request->input('start_date'),
                    $request->input('end_date')
                ]);
            }
            // Paginação: Retorna 10 resultados por página
            $surgeries = $query->paginate(10);

            return response()->json($surgeries);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // 
    public function details($id)
{
    try {
        $details = DB::table('surgeries')
            ->leftJoin('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id')
            ->leftJoin('professionals as cirurgiao', 'surgeries.cirurgiao_id', '=', 'cirurgiao.id')
            ->leftJoin('professionals as anestesista', 'surgeries.anestesista_id', '=', 'anestesista.id')
            ->leftJoin('professionals as pediatra', 'surgeries.pediatra_id', '=', 'pediatra.id')
            ->leftJoin('professionals as enfermeiro', 'surgeries.enfermeiro_id', '=', 'enfermeiro.id')
            ->leftJoin('cities', 'surgeries.citie_id', '=', 'cities.id')
            ->leftJoin('states', 'surgeries.state_id', '=', 'states.id')
            ->leftJoin('indications', 'surgeries.indication_id', '=', 'indications.id')
            ->select(
                'surgeries.date',
                'surgeries.time',
                'surgeries.name as paciente_name',
                'surgeries.age',
                'states.name as state_name',
                'cities.name as city_name',
                'surgeries.medical_record as prontuario',
                'surgeries.origin_department as origem_setor',
                'indications.descricao as indication',
                'surgeries.anesthesia',
                'anestesista.name as anestesista_name',
                'pediatra.name as pediatra_name',
                'enfermeiro.name as enfermeiro_name',
                'surgeries.admission_date as data_admissao',
                'surgeries.admission_time as hora_admissao',
                'surgeries.end_time as hora_termino',
                'surgeries.apgar',
                'surgeries.ligation',
                'surgeries.social_status',
                'surgery_types.tipo as tipo_cirurgia',
                'cirurgiao.name as cirurgiao_name'
            )
            ->where('surgeries.id', $id)
            ->first();

        if (!$details) {
            return response()->json(['error' => 'Detalhes não encontrados.'], 404);
        }

        return response()->json(['data' => $details]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao buscar detalhes: ' . $e->getMessage()], 500);
    }
}

}
