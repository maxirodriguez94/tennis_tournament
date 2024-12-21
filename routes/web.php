<?php

use App\Http\Controllers\MatchController;
use App\Http\Controllers\TournamentController;




Route::get('/simulate-tournament', [TournamentController::class, 'simulateTournament']);

Route::get('/matches', [TournamentController::class, 'getTournamentWithMatches']);