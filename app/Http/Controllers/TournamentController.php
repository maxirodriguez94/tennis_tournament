<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\TournamentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    private $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    public function simulateTournament(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gender' => 'required|string|in:Masculino,Femenino',
            'type' => 'required|string|in:single,doubles',
        ]);

        $gender = $validated['gender'];
        $type = $validated['type'];

        $players = Player::where('gender', $gender)->take(8)->get();

        if ($players->count() < 8) {
            return response()->json([
                'error' => 'No hay suficientes jugadores para simular el torneo.'
            ], 400);
        }

        $isDoubles = $type === 'doubles';

        $result = $this->tournamentService->simulateTournament($players, $isDoubles, $gender);

        $matchResults = collect($result['match_results'])->map(function ($match) {
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
            'winner' => $result['winner'],
            'match_results' => $matchResults,
        ]);
    }
}
