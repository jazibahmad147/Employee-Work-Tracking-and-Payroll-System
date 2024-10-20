<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MitarbeitersController;
use App\Http\Controllers\FestivalsController;
use App\Http\Controllers\BezeichnungsController;
use App\Http\Controllers\MitarbeiterstundenController;
use App\Http\Controllers\GeldsController;
use App\Http\Controllers\RechnungsController;
use App\Http\Controllers\BerichteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(session()->has('email')){
        return redirect('dashboard');
    }
    return view('index');
});
Route::post('register',[UsersController::class,'register']);
Route::post('login',[UsersController::class,'login']);
Route::get('/logout', function () {
    if(session()->has('email')){
        session()->pull('email',null);
    }
    return redirect('/');
});


// --WebGaurd-- is a Middleware which protect pages to open without having session
// add --WebGaurd-- path as 'sessionGaurd' of middleware App\Http\Kernel.php  (under 'protected $routeMiddleware')
// Route::get('dashboard',[DashboardController::class,'dashboard'])->Middleware('sessionGaurd');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->Middleware('sessionGaurd');
Route::post('/fetch-mitarbeiter-data', [DashboardController::class, 'fetchMitarbeiterData'])->name('fetchMitarbeiterData')->Middleware('sessionGaurd');
// Mitarbeiter 
Route::match(['get', 'post'], 'mitarbeiters', [MitarbeitersController::class, 'view'])->name('mitarbeiters')->middleware('sessionGaurd');
Route::post('addMitarbeiter',[MitarbeitersController::class,'register'])->Middleware('sessionGaurd');
Route::get('fetch_mitarbeiter_by_id/{id}',[MitarbeitersController::class,'fetch'])->Middleware('sessionGaurd');
Route::post('updateMitarbeiter',[MitarbeitersController::class,'update'])->Middleware('sessionGaurd');
Route::get('mitarbeiter/delete/{id}',[MitarbeitersController::class,'delete'])->Middleware('sessionGaurd');
// Festival 
Route::get('festivals', [FestivalsController::class, 'view'])->name('festivals')->middleware('sessionGaurd');
Route::post('addFestival',[FestivalsController::class,'register'])->Middleware('sessionGaurd');
Route::get('fetch_festival_by_id/{id}',[FestivalsController::class,'fetch'])->Middleware('sessionGaurd');
Route::post('updateFestival',[FestivalsController::class,'update'])->Middleware('sessionGaurd');
Route::get('festival/delete/{id}',[FestivalsController::class,'delete'])->Middleware('sessionGaurd');
// Bezeichnung
Route::get('bezeichnungs', [BezeichnungsController::class, 'view'])->name('bezeichnungs')->middleware('sessionGaurd');
Route::post('addBezeichnung',[BezeichnungsController::class,'register'])->Middleware('sessionGaurd');
Route::get('fetch_bezeichnung_by_id/{id}',[BezeichnungsController::class,'fetch'])->Middleware('sessionGaurd');
Route::post('updateBezeichnung',[BezeichnungsController::class,'update'])->Middleware('sessionGaurd');
Route::get('bezeichnung/delete/{id}',[BezeichnungsController::class,'delete'])->Middleware('sessionGaurd');
// Mitarbeiterstunden
Route::match(['get', 'post'], 'mitarbeiterstunden', [MitarbeiterstundenController::class, 'view'])->name('mitarbeiters')->middleware('sessionGaurd');
Route::post('/fetchMitarbeiterName', [MitarbeiterstundenController::class, 'fetchMitarbeiterName'])->middleware('sessionGaurd');
Route::post('/fetchFestivalName', [MitarbeiterstundenController::class, 'fetchFestivalName'])->middleware('sessionGaurd');
Route::post('/fetchBezeichnungName', [MitarbeiterstundenController::class, 'fetchBezeichnungName'])->middleware('sessionGaurd');
Route::post('addMitarbeiterstunden',[MitarbeiterstundenController::class,'register'])->Middleware('sessionGaurd');
Route::get('fetch_mitarbeiterstunden_by_id/{id}', [MitarbeiterstundenController::class, 'fetchById'])->Middleware('sessionGaurd');
Route::post('updateMitarbeiterstunden',[MitarbeiterstundenController::class,'update'])->Middleware('sessionGaurd');
Route::get('mitarbeiterstunden/delete/{id}',[MitarbeiterstundenController::class,'delete'])->Middleware('sessionGaurd');
// Geld 
Route::get('geld', [GeldsController::class, 'view'])->name('gelds')->middleware('sessionGaurd');
Route::post('/fetchMitarbeiterName', [MitarbeiterstundenController::class, 'fetchMitarbeiterName'])->middleware('sessionGaurd');
Route::post('addGeld',[GeldsController::class,'register'])->Middleware('sessionGaurd');
Route::get('fetch_geld_by_id/{id}',[GeldsController::class,'fetchById'])->Middleware('sessionGaurd');
Route::post('updateGeld',[GeldsController::class,'update'])->Middleware('sessionGaurd');
Route::get('geld/delete/{id}',[GeldsController::class,'delete'])->Middleware('sessionGaurd');
// Rechnung
Route::get('rechnung', [RechnungsController::class, 'viewRechnungBlade'])->middleware('sessionGaurd');
Route::post('addRechnung',[RechnungsController::class,'addRechnung'])->Middleware('sessionGaurd');
Route::match(['get', 'post'], 'rechnung-verwalten', [RechnungsController::class, 'viewRechnungs'])->middleware('sessionGaurd');
Route::post('fetchRechnungDetails', [RechnungsController::class, 'fetchRechnungDetails'])->name('fetchRechnungDetails')->middleware('sessionGaurd');
Route::get('edit-rechnung/{id}', [RechnungsController::class, 'viewEditRechnungBlade'])->middleware('sessionGaurd');
Route::post('updateRechnung', [RechnungsController::class, 'updateRechnung'])->middleware('sessionGaurd');
Route::get('rechnung/delete/{id}',[RechnungsController::class,'deleteRechnung'])->Middleware('sessionGaurd');
// Berichte
Route::match(['get', 'post'], 'stundenbericht', [BerichteController::class, 'viewStundenBricht'])->middleware('sessionGaurd');
Route::match(['get', 'post'], 'mitarbeiterbericht', [BerichteController::class, 'viewMitarbeiterBricht'])->middleware('sessionGaurd');
Route::match(['get', 'post'], 'festivalbericht', [BerichteController::class, 'viewFestivalBricht'])->middleware('sessionGaurd');