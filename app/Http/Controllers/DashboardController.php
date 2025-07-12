<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    public function index(Request $request)
    {
        // Obtenha as datas ou defina valores padrão para carregar todos os dados
        $startDate = $request->get('start_date', '1900-01-01');
        $endDate = $request->get('end_date', now());

        // Total de cirurgias
        $totalCirurgias = DB::table('surgeries') ->whereBetween('date', [$startDate, $endDate])
        ->count();



        $cirurgiasObstetricas = DB::table('surgeries')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('surgery_id', function ($query) {
                $query->select('id')
                    ->from('surgery_types')
                    ->where('tipo', 'obstetrica');
            })
            ->count();

        // Cirurgias Ginecológicas
        $cirurgiasGinecologicas = DB::table('surgeries')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('surgery_id', function ($query) {
                $query->select('id')
                    ->from('surgery_types')
                    ->where('tipo', 'ginecologica');
            })
            ->count();

        // Cirurgias Pediátricas
        $cirurgiasPediatricas = DB::table('surgeries')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('surgery_id', function ($query) {
                $query->select('id')
                    ->from('surgery_types')
                    ->where('tipo', 'pediatrica')
                    ->limit(1);
            })
            ->count();

        $cirurgiasPediatricas = DB::table('surgeries')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('surgery_id', function ($query) {
                $query->select('id')
                    ->from('surgery_types')
                    ->where('tipo', 'pediatrica');
            })
            ->count();

        // APGAR abaixo de 7
        $apgarAbaixoDeSete = DB::table('surgeries')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('apgar', '<', 7)
            ->count();

        // Cirurgia mais realizada
        $cirurgiaMaisRealizada = DB::table('surgeries')
            ->join('surgery_types', 'surgeries.surgery_id', '=', 'surgery_types.id')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('surgery_types.descricao as tipo_cirurgia', DB::raw('COUNT(surgeries.id) as total'))
            ->groupBy('surgery_types.descricao')
            ->orderBy('total', 'desc')
            ->first();

        // Cirurgias por cidade
        $cirurgiasPorCidade = DB::table('surgeries')
            ->join('cities', 'surgeries.citie_id', '=', 'cities.id')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('cities.name as city', DB::raw('COUNT(surgeries.id) as total'))
            ->groupBy('cities.name')
            ->orderBy('total', 'desc')
            ->get();
        // Dados para cirurgias por cirurgião
        $cirurgiasPorCirurgiao = DB::table('surgeries')
            ->join('professionals', 'surgeries.cirurgiao_id', '=', 'professionals.id')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('professionals.name as cirurgiao', DB::raw('COUNT(surgeries.id) as total'))
            ->groupBy('surgeries.cirurgiao_id', 'professionals.name')
            ->get();

        // Retorna a view com os dados
        return view('dashboard', [
            'totalCirurgias' => $totalCirurgias,
            'cirurgiasObstetricas' => $cirurgiasObstetricas,
            'cirurgiasGinecologicas' => $cirurgiasGinecologicas,
            'cirurgiasPediatricas' => $cirurgiasPediatricas,
            'apgarAbaixoDeSete' => $apgarAbaixoDeSete,
            'cirurgiaMaisRealizada' => $cirurgiaMaisRealizada,
            'cirurgiasPorCidade' => $cirurgiasPorCidade,
            'cirurgiasPorCirurgiao' => $cirurgiasPorCirurgiao,
        ]);
    }
}
