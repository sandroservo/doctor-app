<?php

namespace App\Http\Controllers\Relatorios;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use Illuminate\Http\Request;
use App\Models\Surgery;
use PDF;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $surgeons = Professional::all();

        // $query = Surgery::query();

        // if ($request->has('cirurgiao_id') && $request->cirurgiao_id) {
        //     $query->where('cirurgiao_id', $request->cirurgiao_id);
        // }

        // if ($request->has('start_date') && $request->start_date) {
        //     $query->where('date', '>=', $request->start_date);
        // }

        // if ($request->has('end_date') && $request->end_date) {
        //     $query->where('date', '<=', $request->end_date);
        // }

        // $surgeries = $query->with('cirurgiao', 'surgery_type')->paginate(5)->appends($request->all());

        // // Se o botão "Gerar PDF" foi clicado
        // $showPdf = $request->has('show_pdf') ? route('reports.surgeries.pdf', $request->all()) : null;

        // return view('reports.rl01.index', compact('surgeons', 'surgeries', 'showPdf'));
        // Obtém todos os profissionais do tipo "cirurgião"
        $surgeons = Professional::where('specialty', 'c')->get();

        // Inicia a consulta para a tabela de cirurgias
        $query = Surgery::query();

        // Verifica se o filtro por "cirurgião_id" foi enviado na requisição e se o valor não é vazio
        if ($request->has('cirurgiao_id') && $request->cirurgiao_id) {
            // Adiciona uma condição à consulta para filtrar pelo ID do cirurgião
            $query->where('cirurgiao_id', $request->cirurgiao_id);
        }

        // Verifica se a data de início foi enviada na requisição e se o valor não é vazio
        if ($request->has('start_date') && $request->start_date) {
            // Adiciona uma condição à consulta para filtrar as cirurgias com data maior ou igual à data de início
            $query->where('date', '>=', $request->start_date);
        }

        // Verifica se a data de término foi enviada na requisição e se o valor não é vazio
        if ($request->has('end_date') && $request->end_date) {
            // Adiciona uma condição à consulta para filtrar as cirurgias com data menor ou igual à data de término
            $query->where('date', '<=', $request->end_date);
        }

        // Executa a consulta, carregando as relações "cirurgião" e "surgery_type",
        // e paginando os resultados para exibir 5 por página
        $surgeries = $query->with('cirurgiao', 'surgery_type')->paginate(5)->appends($request->all());

        // Verifica se o botão "Gerar PDF" foi clicado, ou seja, se o parâmetro "show_pdf" existe na requisição
        $showPdf = $request->has('show_pdf') ? route('reports.surgeries.pdf', $request->all()) : null;

        // Retorna a view para exibir os dados, passando os cirurgiões, as cirurgias filtradas e o link para o PDF (se aplicável)
        return view('reports.rl01.index', compact('surgeons', 'surgeries', 'showPdf'));
    }

    public function generatePDF(Request $request)
    {
        $query = Surgery::query();

        // Aplicando os filtros fornecidos na requisição
        if ($request->has('cirurgiao_id') && $request->cirurgiao_id) {
            $query->where('cirurgiao_id', $request->cirurgiao_id);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        // Certifique-se de carregar os relacionamentos necessários
        $surgeries = $query->with(['surgery_type', 'cirurgiao'])->get();

        // Verifica e trata registros sem relacionamento para evitar erro
        $surgeries->each(function ($surgery) {
            if (!$surgery->surgery_type) {
                $surgery->surgery_type = (object)['descricao' => 'Tipo não especificado']; // Adiciona um valor padrão
            }
        });

        $month = $request->start_date
            ? Carbon::parse($request->start_date)->format('F Y')
            : Carbon::now()->format('F Y');

        // Gera o PDF com os dados e define o tamanho do papel
        $pdf = PDF::loadView('reports.rl01.pdf', compact('surgeries', 'month'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream(); // Retorna o PDF para ser exibido no navegador
    }
}
