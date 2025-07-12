<?php

namespace App\Http\Controllers\Dashboads;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\Surgery;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PdfDashboardController extends Controller
{
    public function generatePdf(Request $request)
    {
        $surgeonId = $request->query('surgeon_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Obter o nome do cirurgião
        $surgeon = Professional::find($surgeonId);

        // Filtrar cirurgias pelo cirurgião e período
        $query = Surgery::with(['city', 'state', 'anestesista', 'cirurgiao', 'pediatra', 'enfermeiro'])
            ->where('cirurgiao_id', $surgeonId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $surgeries = $query->orderBy('date', 'asc')->get();

        // Gerar o PDF
        $pdf = Pdf::loadView('pdfdashboard.index', compact('surgeries', 'surgeon', 'startDate', 'endDate'));

        // Definir o papel como A4 (paisagem)
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio-cirurgias.pdf');

        // $surgeonId = $request->query('surgeon_id');
        // $startDate = $request->query('start_date');
        // $endDate = $request->query('end_date');

        // // Obter o nome do cirurgião
        // $surgeon = Professional::select('id', 'name')->find($surgeonId);

        // // Filtrar cirurgias pelo cirurgião e período
        // $query = Surgery::with([
        //     'city:id,name',
        //     'state:id,name',
        //     'anestesista:id,name',
        //     'cirurgiao:id,name',
        //     'pediatra:id,name',
        //     'enfermeiro:id,name'
        // ])
        //     ->where('cirurgiao_id', $surgeonId)
        //     ->select([
        //         'id',
        //         'date',
        //         'time',
        //         'name',
        //         'age',
        //         'citie_id',
        //         'state_id',
        //         'anestesista_id',
        //         'cirurgiao_id',
        //         'pediatra_id',
        //         'enfermeiro_id',
        //         'indication_id',
        //         'surgery_id',
        //         'medical_record',
        //         'admission_date',
        //         'admission_time',
        //         'origin_department',
        //         'anesthesia',
        //         'apgar',
        //         'end_time',
        //         'ligation',
        //         'social_status'
        //     ]);

        // if ($startDate && $endDate) {
        //     $query->whereBetween('date', [$startDate, $endDate]);
        // }

        // // Paginação em blocos para grandes volumes
        // $perPage = 50; // Defina o número de registros por página
        // $chunkedPdfContent = '';

        // $query->orderBy('date', 'asc')->chunk($perPage, function ($surgeriesChunk) use (&$chunkedPdfContent, $surgeon, $startDate, $endDate) {
        //     $chunkedPdfContent .= View::make('pdfdashboard.index', [
        //         'surgeries' => $surgeriesChunk,
        //         'surgeon' => $surgeon,
        //         'startDate' => $startDate,
        //         'endDate' => $endDate
        //     ])->render();
        // });

        // // Gerar o PDF em um único arquivo
        // $pdf = Pdf::loadHTML($chunkedPdfContent);

        // // Definir o papel como A4 (paisagem)
        // $pdf->setPaper('a4', 'landscape');

        // return $pdf->stream('relatorio-cirurgias.pdf');

    }  
}
