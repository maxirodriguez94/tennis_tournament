<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\TournamentService;
use Illuminate\Http\JsonResponse;

class TournamentController extends Controller
{
    private $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    public function simulateTournament(): JsonResponse
    {
        $players = Player::where('gender', 'Masculino')->take(8)->get();

        $resultSingle = $this->tournamentService->simulateTournament($players, false);
        $resultDoubles = $this->tournamentService->simulateTournament($players, true);

        $singleMatchResults = collect($resultSingle['match_results'])->map(function ($match) {
            return [
                'id' => $match['id'] ?? 'Desconocido',
                'team_a' => $match['team_a'] ?? 'Desconocido',
                'team_b' => $match['team_b'] ?? 'Desconocido',
                'winner' => $match['winner'] ?? 'Desconocido',
                'score_a' => $match['score_a'] ?? 0,
                'score_b' => $match['score_b'] ?? 0,
            ];
        });

        $doublesMatchResults = collect($resultDoubles['match_results'])->map(function ($match) {
            return [
                'id' => $match['id'] ?? 'Desconocido',
                'team_a' => $match['team_a'] ?? 'Desconocido',
                'team_b' => $match['team_b'] ?? 'Desconocido',
                'winner' => $match['winner'] ?? 'Desconocido',
                'score_a' => $match['score_a'] ?? 0,
                'score_b' => $match['score_b'] ?? 0,
            ];
        });

        return response()->json([
            'single_winner' => $resultSingle['winner'],
            'doubles_winners' => $resultDoubles['winner'],
            'match_results' => [
                'single' => $singleMatchResults,
                'doubles' => $doublesMatchResults,
            ],
        ]);
    }
}
