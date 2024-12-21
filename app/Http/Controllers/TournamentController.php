<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Tournament;
use App\Services\TournamentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;

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
            'players' => 'required|integer|min:8',
        ]);

        $gender = $validated['gender'];
        $type = $validated['type'];
        $numPlayers = $validated['players'];

        $players = Player::where('gender', $gender)->take($numPlayers)->get();

        if ($players->count() < 8) {
            return response()->json([
                'error' => 'No hay suficientes jugadores para simular el torneo.'
            ], 400);
        }

        $tournament = Tournament::create([
            'name' => 'Tournament ' . now()->format('Y-m-d H:i:s'),
            'is_doubles' => $type === 'doubles',
            'gender' => $gender,
        ]);

        $isDoubles = $type === 'doubles';

        try {
            $result = $this->tournamentService->simulateTournament($players, $isDoubles, $gender, $tournament);

            $matchResults = collect($result['match_results'])->map(function ($match) {
                return [
                    'id' => $match['id'] ?? 'Desconocido',
                    'team_a' => $match['team_a'] ?? 'Desconocido',
                    'team_b' => $match['team_b'] ?? 'Desconocido',
                    'winner' => $match['winner'] ?? 'Desconocido',
                    'score_a' => $match['score_a'] ?? 0,
                    'score_b' => $match['score_b'] ?? 0,
                    'round' => $match['round']
                ];
            });

            return response()->json([
                'winner' => $result['winner'],
                'match_results' => $matchResults,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al simular el torneo.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTournamentWithMatches(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tournament_id' => 'required|integer|exists:tournaments,id',
        ]);
    
        $tournament = Tournament::with('matches')->find(59);
    
        if (!$tournament) {
            return response()->json([
                'error' => 'Torneo no encontrado.',
            ], 404);
        }

        Log::info('Partidos encontrados:', ['count' => $tournament->matches->count()]);
    
        $formattedMatches = $tournament->matches->map(function ($match) {
            return [
                'id' => $match->id,
                'team_a' => json_decode($match->team_a, true),
                'team_b' => json_decode($match->team_b, true),
                'winner' => json_decode($match->winner, true),
                'score_a' => $match->score_a,
                'score_b' => $match->score_b,
                'created_at' => $match->created_at,
                'updated_at' => $match->updated_at,
            ];
        });
    
        return response()->json([
            'tournament_name' => $tournament->name,
            'tournament_gender' => $tournament->gender,
            'is_doubles' => $tournament->is_doubles,
            'matches' => $formattedMatches,
        ]);
    }
}    
