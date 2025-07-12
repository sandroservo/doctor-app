<?php

namespace App\Http\Controllers\Relatorios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reports03Controller extends Controller
{
    public function surgeriesByMonth(Request $request)
    {
    //     $month = $request->input('month', now()->format('Y-m'));
    //     $surgeonId = $request->input('surgeon_id', ''); // Recebe o ID do cirurgião

    //     // Recupera todos os cirurgiões para o select box
    //     $surgeons = DB::table('professionals')
    //         ->join('surgeries', 'professionals.id', '=', 'surgeries.cirurgiao_id')
    //         ->distinct()
    //         ->pluck('professionals.name', 'professionals.id'); // Recupera nome e ID

    //     // Filtra cirurgias pelo mês/ano e pelo cirurgião selecionado
    //     $surgeries = DB::table('surgeries')
    //         ->select(
    //             'surgeries.date as data',
    //             'surgeries.time as horario',
    //             'surgeries.name as descricao',
    //             'surgeries.medical_record as prontuario',
    //             'cirurgioes.name as cirurgiao',
    //             'anestesistas.name as anestesista',
    //             'pediatras.name as pediatra',
    //             'enfermeiros.name as enfermeiro'
    //         )
    //         ->join('professionals as cirurgioes', 'surgeries.cirurgiao_id', '=', 'cirurgioes.id')
    //         ->leftJoin('professionals as anestesistas', 'surgeries.anestesista_id', '=', 'anestesistas.id')
    //         ->leftJoin('professionals as pediatras', 'surgeries.pediatra_id', '=', 'pediatras.id')
    //         ->leftJoin('professionals as enfermeiros', 'surgeries.enfermeiro_id', '=', 'enfermeiros.id')
    //         ->whereMonth('surgeries.date', '=', date('m', strtotime($month)))
    //         ->whereYear('surgeries.date', '=', date('Y', strtotime($month)))
    //         ->when($surgeonId, function ($query, $surgeonId) {
    //             return $query->where('surgeries.cirurgiao_id', '=', $surgeonId); // Filtra pelo cirurgião
    //         })
    //         ->orderBy('surgeries.date', 'asc')
    //         ->paginate(10);

    //     return view('reports.rl03.index', compact('surgeries', 'month', 'surgeons', 'surgeonId'));
    $month = $request->input('month', now()->format('Y-m'));
    $surgeonId = $request->input('surgeon_id', '');

    // Recupera todos os cirurgiões para o select box
    $surgeons = DB::table('professionals')
        ->join('surgeries', 'professionals.id', '=', 'surgeries.cirurgiao_id')
        ->distinct()
        ->pluck('professionals.name', 'professionals.id'); // Recupera nome e ID

    // Filtra cirurgias pelo mês/ano e pelo cirurgião selecionado
    $surgeriesQuery = DB::table('surgeries')
        ->join('professionals as cirurgioes', 'surgeries.cirurgiao_id', '=', 'cirurgioes.id')
        ->whereMonth('surgeries.date', '=', date('m', strtotime($month)))
        ->whereYear('surgeries.date', '=', date('Y', strtotime($month)));

    if ($surgeonId) {
        $surgeriesQuery->where('surgeries.cirurgiao_id', '=', $surgeonId);
    }

    // Paginação de cirurgias
    $surgeries = $surgeriesQuery->select(
        'surgeries.date as data',
        'surgeries.time as horario',
        'surgeries.name as descricao',
        'surgeries.medical_record as prontuario',
        'cirurgioes.name as cirurgiao'
    )
    ->orderBy('surgeries.date', 'asc')
    ->paginate(10);

    // Contagem de cirurgias realizadas no mês filtrado
    $totalSurgeries = $surgeriesQuery->count();

    return view('reports.rl03.index', compact('surgeries', 'month', 'surgeons', 'surgeonId', 'totalSurgeries'));
}
}
