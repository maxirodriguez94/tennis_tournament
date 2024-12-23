<?php

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

Route::prefix('tournaments')->group(function () {
    Route::post('/simulate', [TournamentController::class, 'simulateTournament'])->name('tournaments.simulate');
    Route::get('/with-matches', [TournamentController::class, 'getTournamentWithMatches'])->name('tournaments.with.matches');
});
