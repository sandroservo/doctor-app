<?php

namespace App\Http\Controllers\Relatorios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reports05Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Query principal para cirurgias agrupadas
        $surgeries = DB::table('surgeries')
            ->join('professionals as surgeons', 'surgeries.cirurgiao_id', '=', 'surgeons.id')
            ->leftJoin('professionals as anesthetists', 'surgeries.anestesista_id', '=', 'anesthetists.id')
            ->leftJoin('professionals as nurses', 'surgeries.enfermeiro_id', '=', 'nurses.id')
            ->leftJoin('professionals as pediatricians', 'surgeries.pediatra_id', '=', 'pediatricians.id')
            ->join('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id')
            ->select(
                'surgeons.name as surgeon_name',
                'surgery_types.descricao as surgery_description',
                DB::raw('COUNT(surgeries.id) as total_low_apgar'),
                DB::raw('GROUP_CONCAT(CONCAT(surgeries.name, " (", surgeries.time, ")") ORDER BY surgeries.time ASC) as surgery_details'),
                DB::raw('GROUP_CONCAT(DISTINCT anesthetists.name SEPARATOR ", ") as anesthetists'),
                DB::raw('GROUP_CONCAT(DISTINCT nurses.name SEPARATOR ", ") as nurses'),
                DB::raw('GROUP_CONCAT(DISTINCT pediatricians.name SEPARATOR ", ") as pediatricians')
            )
            ->whereRaw("surgeries.apgar REGEXP '^[0-9]+$'")
            ->whereRaw("CAST(surgeries.apgar AS UNSIGNED) < 7")
            ->groupBy('surgeons.name', 'surgery_types.descricao')
            ->paginate(10);

        // Total geral de cirurgias com APGAR < 7
        $totalSurgeries = DB::table('surgeries')
            ->whereRaw("surgeries.apgar REGEXP '^[0-9]+$'")
            ->whereRaw("CAST(surgeries.apgar AS UNSIGNED) < 7")
            ->count();

        return view('reports.rl05.index', [
            'surgeries' => $surgeries,
            'totalSurgeries' => $totalSurgeries, // Envia o total para a view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
