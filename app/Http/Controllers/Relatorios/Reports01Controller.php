<?php

namespace App\Http\Controllers\Relatorios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reports01Controller extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $surgeries = DB::table('surgeries')
            ->select(
                'surgeries.id as cirurgia_id',
                'surgeries.date as data',
                'surgeries.time as horario',
                'surgeries.admission_date as data_admissao',
                'surgeries.admission_time as hora_admissao',
                'surgeries.end_time as hora_termino',
                'surgeries.name as descricao',
                'surgeries.medical_record as prontuario',
                'surgeries.age as idade',
                'states.name as estado',
                'cities.name as cidade',
                'cirurgioes.name as cirurgiao',
                'enfermeiros.name as enfermeiro',
                'pediatras.name as pediatra',
                'anestesistas.name as anestesista',
                'surgeries.anesthesia as anestesia',
                'indications.descricao as indicacao', // Substituído por "description"
                'surgeries.apgar',
                'surgeries.ligation as ligadura',
                'surgeries.social_status as social',
                'surgery_types.descricao as tipo_cirurgia'
            )
            ->join('professionals as cirurgioes', 'surgeries.cirurgiao_id', '=', 'cirurgioes.id')
            ->leftJoin('professionals as anestesistas', 'surgeries.anestesista_id', '=', 'anestesistas.id')
            ->leftJoin('professionals as pediatras', 'surgeries.pediatra_id', '=', 'pediatras.id')
            ->leftJoin('professionals as enfermeiros', 'surgeries.enfermeiro_id', '=', 'enfermeiros.id')
            ->leftJoin('states', 'surgeries.state_id', '=', 'states.id')
            ->leftJoin('cities', 'surgeries.citie_id', '=', 'cities.id')
            ->leftJoin('indications', 'surgeries.indication_id', '=', 'indications.id') // Usando o nome correto da coluna
            ->leftJoin('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id')
            ->whereMonth('surgeries.date', '=', date('m', strtotime($month)))
            ->whereYear('surgeries.date', '=', date('Y', strtotime($month)))
            ->orderBy('surgeries.date', 'asc') // Ordenação por data
            ->paginate(10);

        return view('reports.rl02.index', compact('surgeries', 'month'));
    }
}



