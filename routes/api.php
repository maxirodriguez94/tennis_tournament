<?php
/**
 * @OA\Info(
 *     title="Documentación de la API de Torneos",
 *     version="1.0.0",
 *     description="Esta es la documentación de la API para la gestión de torneos.",
 *     @OA\Contact(
 *         email="soporte@example.com"
 *     )
 * )
 */

use App\Http\Controllers\TournamentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Bienvenido al servidor Tennis Tournament'
    ]);
})->name('welcome');

Route::prefix('tournaments')->group(function () {
    Route::post('/simulate', [TournamentController::class, 'simulateTournament'])->name('tournaments.simulate');
    Route::get('/with-matches', [TournamentController::class, 'getTournamentWithMatches'])->name('tournaments.with.matches');
});
