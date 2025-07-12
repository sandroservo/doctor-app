<?php

namespace App\Http\Controllers\Relatorios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reports04Controller extends Controller
{
    public function index()
    {
        $surgeries = DB::table('surgeries')
            ->join('cities', 'surgeries.citie_id', '=', 'cities.id')
            ->select(
                'cities.name as city_name',
                'surgeries.citie_id',
                DB::raw('COUNT(surgeries.id) as total_surgeries'),
                DB::raw('ROUND(COUNT(surgeries.id) * 100.0 / (SELECT COUNT(*) FROM surgeries), 2) as percentage')
            )
            ->groupBy('cities.name', 'surgeries.citie_id')
            ->paginate(10); // Paginação com 10 itens por página

        $totalSurgeries = DB::table('surgeries')->count(); // Total geral de cirurgias

        return view('reports.rl04.index', [
            'surgeries' => $surgeries,
            'totalSurgeries' => $totalSurgeries,
        ]);
    }

    public function cityDetails($city_id)
    {
        // Buscar detalhes das cirurgias da cidade com paginação
        $details = DB::table('surgeries')
            ->join('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id')
            ->select(
                'surgery_types.descricao as surgery_type',
                DB::raw('COUNT(surgeries.id) as total_surgeries'),
                DB::raw('ROUND(COUNT(surgeries.id) * 100.0 / (SELECT COUNT(*) FROM surgeries), 2) as percentage')
            )
            ->where('surgeries.citie_id', $city_id)
            ->groupBy('surgery_types.descricao')
            ->orderBy('total_surgeries', 'DESC')
            ->paginate(10); // Paginação com 10 itens por página

        // Buscar a cidade pelo ID
        $city = DB::table('cities')->where('id', $city_id)->first();

        return view('reports.rl04.details', [
            'details' => $details,
            'city' => $city,
        ]);
    }

    public function show($city_id)
    {
        $surgeries = // Mesma query acima;
            $details = DB::table('surgeries')
            ->join('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id')
            ->select(
                'surgery_types.descricao as surgery_type',
                DB::raw('COUNT(surgeries.id) as total_surgeries'),
                DB::raw('ROUND(COUNT(surgeries.id) * 100.0 / (SELECT COUNT(*) FROM surgeries), 2) as percentage')
            )
            ->where('surgeries.citie_id', $city_id)
            ->groupBy('surgery_types.descricao')
            ->orderBy('total_surgeries', 'DESC')
            ->get();

        return view('reports.rl04.details', [
            'surgeries' => $surgeries,
            'details' => $details,
            'city_id' => $city_id,
        ]);
    }
}
