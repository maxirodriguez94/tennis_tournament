<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;


Route::get('/simulate-tournament', [TournamentController::class, 'simulateTournament']);
