<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndicationController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Relatorios\ReportsController;
use App\Http\Controllers\Relatorios\Reports01Controller;
use App\Http\Controllers\Relatorios\Reports03Controller;
use App\Http\Controllers\Relatorios\Reports04Controller;
use App\Http\Controllers\Relatorios\Reports05Controller;
use App\Http\Controllers\Relatorios\Reports06Controller;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\SurgeryTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('dashboard');
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard',  function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard2', [DashboardController::class, 'index'])->name('dashboard2');


Route::resource('/surgeries', SurgeryController::class)
    ->only(['index', 'store', 'edit', 'create', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('professionals', ProfessionalController::class)
    ->only(['index', 'store', 'edit', 'create', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('/surgery_types', SurgeryTypeController::class)
    ->only(['index', 'store', 'edit', 'create', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('/indications', IndicationController::class)
    ->only(['index', 'store', 'edit', 'create', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::get('/get-cities/{state_id}', [SurgeryController::class, 'getCities'])->name('get.cities');


Route::get('surgeries/{id}/report', [SurgeryController::class, 'report'])->name('surgeries.report');
Route::get('/surgeries/relatorio', [RelatorioController::class, 'index'])->name('relatorio.index');

Route::get('/surgeries/bi', [DashboardController::class, 'index'])->name('bi.index');
Route::get('/relatorio/cirurgias/pdf', [RelatorioController::class, 'gerarPdf'])->name('relatorio.pdf');
Route::get('/relatorio/cirurgia/{id}/pdf', [RelatorioController::class, 'gerarPdfCirurgia'])->name('relatorio.cirurgia.pdf');

Route::get('/reports/generate', [ReportController::class, 'index'])->name('reports.generate');
Route::post('/reports/generate-pdf', [ReportController::class, 'generatePdf'])->name('reports.generatePdf');
Route::get('/reports/surgery_percentage', [ReportController::class, 'surgeryPercentageByCity'])->name('reports.surgery_percentage');

Route::get('/reports', [ReportController::class, 'geral'])->name('reports.index');

Route::get('/reports/total_surgeries', [ReportController::class, 'totalSurgeries'])->name('reports.total_surgeries');
Route::get('/reports/specified_surgeries', [ReportController::class, 'specifiedSurgeries'])->name('reports.specified_surgeries');
Route::get('/reports/obstetric_surgeries', [ReportController::class, 'obstetricSurgeries'])->name('reports.obstetric_surgeries');

Route::get('/reports/gynecologic_surgeries', [ReportController::class, 'gynecologicSurgeries'])->name('reports.gynecologic_surgeries');
Route::get('/reports/pediatric_surgeries', [ReportController::class, 'pediatricSurgeries'])->name('reports.pediatric_surgeries');

Route::get('/reports/surgeries_by_surgeon', [ReportController::class, 'surgeriesBySurgeon'])->name('reports.surgeries_by_surgeon');

//Relarorios
Route::get('/reports/surgeries', [ReportsController::class, 'index'])->name('reports.surgeries');
Route::get('/reports/surgeries/pdf', [ReportsController::class, 'generatePDF'])->name('reports.surgeries.pdf');
Route::get('/reports/surgeries/rl02', [Reports01Controller::class, 'index'])->name('reports.rl02');
Route::get('/reports/surgeries/rl03', [Reports03Controller::class, 'surgeriesByMonth'])->name('reports.rl03');
Route::get('/reports/surgeries/rl04', [Reports04Controller::class, 'index'])->name('reports.rl04');
Route::get('/reports/surgeries/rl04/details/{city_id}', [Reports04Controller::class, 'cityDetails'])
    ->name('reports.rl04.details');
Route::get('/reports/surgeries/rl05', [Reports05Controller::class, 'index'])
    ->name('reports.rl05.apgar');
//Relatorio-outher    
Route::get('/reports/surgeries/rl06', [Reports06Controller::class, 'index'])->name('reports.surgery-rl06');
Route::post('/reports/surgeries/rl06/filter', [Reports06Controller::class, 'filter'])->name('reports.surgery-rl06.filter');
Route::get('/reports/surgeries/rl06/details/{id}', [Reports06Controller::class, 'details'])->name('reports.surgery-rl06.details');



Route::get('/reports/night_surgeries', [ReportController::class, 'nightSurgeries'])->name('reports.night_surgeries');
Route::get('/reports/day_surgeries', [ReportController::class, 'daySurgeries'])->name('reports.day_surgeries');
Route::get('/reports/low_apgar_surgeries', [ReportController::class, 'lowApgarSurgeries'])->name('reports.low_apgar_surgeries');
Route::get('/reports/surgical_indications', [ReportController::class, 'surgicalIndications'])->name('reports.surgical_indications');
Route::get('/reports/surgeries_by_municipality', [ReportController::class, 'surgeriesByMunicipality'])->name('reports.surgeries_by_municipality');

Route::post('/add-surgery', [SurgeryController::class, 'storeSurgery']);
Route::post('/add-indication', [SurgeryController::class, 'storeIndication']);

// Para atualizar a cirurgia (PUT)
Route::get('/register-cad', [RegisterController::class, 'index'])->name('register-cad.index');
Route::post('/register-cad', [RegisterController::class, 'store'])->name('register-cad.index');
Route::get('/surgeries/pdf', [SurgeryController::class, 'exportPdf'])->name('surgeries.pdf');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [ChartController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';
