<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Player;
use App\Models\Tournament;
use App\Services\TournamentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Carbon\Carbon;

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
        'tournament_id' => 'nullable|integer|exists:tournaments,id',
        'gender' => 'nullable|string|in:Masculino,Femenino',
        'startDate' => 'nullable|date',
        'endDate' => 'nullable|date',
    ]);


    $tournamentId = $validated['tournament_id'] ?? null;
    $gender = $validated['gender'] ?? null;
    $startDate = $validated['startDate'] ?? null;
    $endDate = $validated['endDate'] ?? null;

    if ($tournamentId && ($gender || $startDate || $endDate)) {
        return response()->json([
            'error' => 'Si se proporciona el ID del torneo, no se pueden incluir otros parámetros de búsqueda.'
        ], 400);
    }

    if (!$tournamentId && !$gender && !$startDate && !$endDate) {
        return response()->json([
            'error' => 'Debe proporcionar al menos un parámetro de búsqueda.'
        ], 400);
    }

    if ($tournamentId) {
        return $this->getTournamentById($tournamentId);
    }

    $query = Tournament::query();

    if ($gender) {
        $query->where('gender', $gender);
    }

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [$startDate, $endDate]);
    } elseif ($startDate) {
        $query->where('created_at', '>=', $startDate);
    } elseif ($endDate) {
        $query->where('created_at', '<=', $endDate);
    }

    $tournaments = $query->with('matches')->get();

    if ($tournaments->isEmpty()) {
        return response()->json([
            'message' => 'No se encontraron torneos con los criterios proporcionados.'
        ], 404);
    }

    $transformedTournaments = $tournaments->map(function ($tournament) {
        $tournament->matches = $tournament->matches->map(function ($match) {
            $match->team_a = json_decode($match->team_a, true);
            $match->team_b = json_decode($match->team_b, true);
            $match->winner = json_decode($match->winner, true);
            return $match;
        });
        return $tournament;
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Torneos encontrados.',
        'data' => $transformedTournaments,
    ], 200);
}

    public function getTournamentById(int $tournamentId): JsonResponse
    {
        $tournament = Tournament::with('matches')->find($tournamentId);
    
        if (!$tournament) {
            return response()->json(['error' => 'Torneo no encontrado.'], 404);
        }
    
        $tournament->matches = $tournament->matches->map(function ($match) {
            $match->team_a = json_decode($match->team_a, true);
            $match->team_b = json_decode($match->team_b, true);
            $match->winner = json_decode($match->winner, true);
            return $match;
        });
    
        return response()->json([
            'status' => 'success',
            'message' => 'Torneo encontrado.',
            'data' => $tournament,
        ], 200);
    }
}    