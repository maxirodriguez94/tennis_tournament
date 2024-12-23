<?php

namespace App\Http\Controllers;

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

        try {
            $result = $this->tournamentService->simulateTournament($validated);

            return response()->json([
                'winner' => $result['winner'],
                'match_results' => $result['match_results'],
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
        'tournament_id' => 'nullable|string|regex:/^\d+$/',
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
    Log::info("ID request", ['tournament_id' => $tournamentId]);
    $tournaments = $this->tournamentService->getTournamentWithMatches([
        'tournament_id' => $tournamentId,
        'gender' => $gender,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ]);

    if ($tournaments->isEmpty()) {
        return response()->json([
            'message' => 'No se encontraron torneos con los criterios proporcionados.'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Torneos encontrados.',
        'data' => $tournaments,
    ], 200);
    }
}