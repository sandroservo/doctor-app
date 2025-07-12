<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surgery; // Certifique-se de que o modelo da cirurgia está correto
use App\Models\Surgery_type;
use Carbon\Carbon;
use PDF; // Pacote do DomPDF
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        // Busca os tipos de cirurgia disponíveis Surgery_type
        $surgeryTypes = Surgery_type::select('id', 'descricao')->distinct()->get();

        return view('reports.generate', compact('surgeryTypes'));
    }


    public function generatePdf(Request $request)
    {
        $request->validate([
            'surgery_type' => 'required|exists:surgeries,surgery_id',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $surgeryType = $request->surgery_type;
        $month = $request->month;

        $surgeries = Surgery::where('surgery_id', $surgeryType)
            ->whereMonth('date', $month)
            ->with(['surgery_type', 'cirurgiao', 'enfermeiro', 'anestesista', 'pediatra'])
            ->get();

        $surgeryCount = $surgeries->count();

        $pdf = PDF::loadView('reports.pdf', compact('surgeries', 'surgeryCount', 'month'))
            ->setPaper('a4', 'landscape');

        // Salvar o PDF temporariamente
        $pdfPath = storage_path('app/public/relatorio_cirurgia.pdf');
        $pdf->save($pdfPath);

        return response()->json(['url' => asset('storage/relatorio_cirurgia.pdf')]);
    }




    public function surgeryPercentageByCity(Request $request)
    {
        // Gera os dados do relatório e salva o PDF
        $totalSurgeries = Surgery::count();
        $surgeryCountsByCity = Surgery::select('citie_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('citie_id')
            ->with('city')
            ->get();

        $data = $surgeryCountsByCity->map(function ($item) use ($totalSurgeries) {
            return [
                'city' => $item->city->name ?? 'Não Informado',
                'count' => $item->count,
                'percentage' => $totalSurgeries > 0 ? ($item->count / $totalSurgeries) * 100 : 0,
            ];
        });

        $pdf = PDF::loadView('reports.surgery_percentage_by_city', compact('data', 'totalSurgeries'))
            ->setPaper('a4', 'landscape');


        return response()->json(['url' => asset('storage/relatorio_porcentagem_cirurgias.pdf')]);
    }


    public function geral()
    {
        return view('reports.index');
    }

    public function totalSurgeries(Request $request)
    {
        // Define o início e o fim do mês atual
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Busca todas as cirurgias realizadas no mês atual
        $surgeries = Surgery::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with(['cirurgiao', 'enfermeiro', 'anestesista', 'pediatra']) // Inclui as relações necessárias
            ->get();

        // Verifica se o usuário quer gerar o relatório em PDF
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('reports.total_surgeries_pdf', compact('surgeries'));
            return $pdf->stream('relatorio_geral_cirurgias.pdf');
        }

        // Retorna a view com a tabela
        return view('reports.total_surgeries', compact('surgeries'));
    }



    public function surgeriesBySurgeon(Request $request)
    {
        // Define o início e o fim do mês atual
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Busca as cirurgias realizadas no mês atual e agrupa por cirurgião
        $surgeries = Surgery::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with(['cirurgiao', 'enfermeiro', 'anestesista', 'pediatra']) // Carrega a relação com o cirurgião
            ->get()
            ->groupBy('cirurgiao_id'); // Agrupa as cirurgias pelo nome do cirurgião

        // $surgeries = Surgery::whereBetween('date', [$startOfMonth, $endOfMonth])
        //         ->with(['cirurgiao', 'enfermeiro', 'anestesista', 'pediatra']) // Inclui as relações necessárias
        //         ->get();

        // Verifica se o usuário quer gerar o relatório em PDF
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('reports.surgeries_by_surgeon_pdf', compact('surgeries'))
            ->setPaper('a4', 'landscape');
            return $pdf->stream('relatorio_cirurgias_por_cirurgiao.pdf');

            
        }

        // Retorna a view com a tabela
        return view('reports.surgeries_by_surgeon', compact('surgeries'));
    }
}
