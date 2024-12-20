<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;

// Ruta para simular el torneo
Route::get('/simulate-tournament', [TournamentController::class, 'simulateTournament']);
