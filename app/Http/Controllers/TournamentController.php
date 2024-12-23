<?php

namespace App\Http\Controllers;

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
/**
 * @OA\Post(
 *     path="/api/tournaments/simulate",
 *     tags={"Tournaments"},
 *     summary="Simular torneo",
 *     description="Simula un torneo con los datos proporcionados.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="gender",
 *                 type="string",
 *                 description="Género del torneo",
 *                 enum={"Masculino", "Femenino"},
 *                 example="Femenino"
 *             ),
 *             @OA\Property(
 *                 property="type",
 *                 type="string",
 *                 description="Tipo de torneo (individual o dobles)",
 *                 enum={"single", "doubles"},
 *                 example="doubles"
 *             ),
 *             @OA\Property(
 *                 property="players",
 *                 type="integer",
 *                 description="Cantidad de jugadores participantes (mínimo 8)",
 *                 example=8
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Torneo simulado con éxito",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="winner",
 *                 type="array",
 *                 description="Ganador del torneo",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=21),
 *                     @OA\Property(property="name", type="string", example="Serena Williams"),
 *                     @OA\Property(property="skill_level", type="integer", example=97),
 *                     @OA\Property(property="gender", type="string", example="Femenino"),
 *                     @OA\Property(property="strength", type="integer", example=0),
 *                     @OA\Property(property="speed", type="integer", example=0),
 *                     @OA\Property(property="reaction_time", type="integer", example=95),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="match_results",
 *                 type="array",
 *                 description="Resultados de los partidos",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="ID del partido", example=4),
 *                     @OA\Property(
 *                         property="team_a",
 *                         type="array",
 *                         description="Equipo A",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="id", type="integer", example=27),
 *                             @OA\Property(property="name", type="string", example="Petra Kvitová"),
 *                             @OA\Property(property="skill_level", type="integer", example=89),
 *                             @OA\Property(property="gender", type="string", example="Femenino"),
 *                             @OA\Property(property="strength", type="integer", example=0),
 *                             @OA\Property(property="speed", type="integer", example=0),
 *                             @OA\Property(property="reaction_time", type="integer", example=86),
 *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z"),
 *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z")
 *                         )
 *                     ),
 *                     @OA\Property(
 *                         property="team_b",
 *                         type="array",
 *                         description="Equipo B",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="id", type="integer", example=21),
 *                             @OA\Property(property="name", type="string", example="Serena Williams"),
 *                             @OA\Property(property="skill_level", type="integer", example=97),
 *                             @OA\Property(property="gender", type="string", example="Femenino"),
 *                             @OA\Property(property="strength", type="integer", example=0),
 *                             @OA\Property(property="speed", type="integer", example=0),
 *                             @OA\Property(property="reaction_time", type="integer", example=95),
 *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z"),
 *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z")
 *                         )
 *                     ),
 *                     @OA\Property(
 *                         property="winner",
 *                         type="array",
 *                         description="Equipo ganador",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="id", type="integer", example=21),
 *                             @OA\Property(property="name", type="string", example="Serena Williams"),
 *                             @OA\Property(property="skill_level", type="integer", example=97),
 *                             @OA\Property(property="gender", type="string", example="Femenino"),
 *                             @OA\Property(property="reaction_time", type="integer", example=95)
 *                         )
 *                     ),
 *                     @OA\Property(property="score_a", type="integer", description="Puntaje del Equipo A", example=181),
 *                     @OA\Property(property="score_b", type="integer", description="Puntaje del Equipo B", example=190),
 *                     @OA\Property(property="round", type="integer", description="Número de ronda", example=1)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Datos inválidos",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Datos inválidos")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Ocurrió un error al simular el torneo."),
 *             @OA\Property(property="details", type="string", example="Error inesperado.")
 *         )
 *     )
 * )
 */

public function simulateTournament(Request $request)    
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

 /**
 * @OA\Get(
 *     path="/api/tournaments/with-matches",
 *     summary="Obtiene torneos con partidos asociados",
 *     description="Devuelve una lista de torneos junto con los detalles de sus partidos según los criterios proporcionados. Si se incluye el ID del torneo, no se permiten otros parámetros de búsqueda.",
 *     tags={"Tournaments"},
 *     @OA\Parameter(
 *         name="tournament_id",
 *         in="query",
 *         description="ID único del torneo. Si se proporciona, no se pueden incluir otros parámetros.",
 *         required=false,
 *         @OA\Schema(type="string", example="123")
 *     ),
 *     @OA\Parameter(
 *         name="gender",
 *         in="query",
 *         description="Género del torneo. Valores posibles: 'Masculino' o 'Femenino'.",
 *         required=false,
 *         @OA\Schema(type="string", enum={"Masculino", "Femenino"}, example="Masculino")
 *     ),
 *     @OA\Parameter(
 *         name="startDate",
 *         in="query",
 *         description="Fecha de inicio del rango de búsqueda en formato YYYY-MM-DD.",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2023-01-01")
 *     ),
 *     @OA\Parameter(
 *         name="endDate",
 *         in="query",
 *         description="Fecha de fin del rango de búsqueda en formato YYYY-MM-DD.",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2023-12-31")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Torneos encontrados con éxito.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Torneos encontrados."),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Tournament 2024-12-23 21:33:41"),
 *                     @OA\Property(property="is_doubles", type="boolean", example=true),
 *                     @OA\Property(property="gender", type="string", example="Femenino"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-23T21:33:41.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-23T21:33:41.000000Z"),
 *                     @OA\Property(
 *                         property="matches",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="id", type="integer", example=1),
 *                             @OA\Property(property="tournament_id", type="integer", example=1),
 *                             @OA\Property(
 *                                 property="team_a",
 *                                 type="array",
 *                                 @OA\Items(
 *                                     type="object",
 *                                     @OA\Property(property="id", type="integer", example=27),
 *                                     @OA\Property(property="name", type="string", example="Petra Kvitová"),
 *                                     @OA\Property(property="skill_level", type="integer", example=89),
 *                                     @OA\Property(property="gender", type="string", example="Femenino"),
 *                                     @OA\Property(property="strength", type="integer", example=0),
 *                                     @OA\Property(property="speed", type="integer", example=0),
 *                                     @OA\Property(property="reaction_time", type="integer", example=86),
 *                                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z"),
 *                                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z")
 *                                 )
 *                             ),
 *                             @OA\Property(
 *                                 property="team_b",
 *                                 type="array",
 *                                 @OA\Items(
 *                                     type="object",
 *                                     @OA\Property(property="id", type="integer", example=28),
 *                                     @OA\Property(property="name", type="string", example="Victoria Azarenka"),
 *                                     @OA\Property(property="skill_level", type="integer", example=91),
 *                                     @OA\Property(property="gender", type="string", example="Femenino"),
 *                                     @OA\Property(property="strength", type="integer", example=0),
 *                                     @OA\Property(property="speed", type="integer", example=0),
 *                                     @OA\Property(property="reaction_time", type="integer", example=88),
 *                                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z"),
 *                                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-23T21:33:39.000000Z")
 *                                 )
 *                             ),
 *                             @OA\Property(
 *                                 property="winner",
 *                                 type="array",
 *                                 @OA\Items(
 *                                     type="object",
 *                                     @OA\Property(property="id", type="integer", example=28),
 *                                     @OA\Property(property="name", type="string", example="Victoria Azarenka"),
 *                                     @OA\Property(property="skill_level", type="integer", example=91),
 *                                     @OA\Property(property="gender", type="string", example="Femenino"),
 *                                     @OA\Property(property="reaction_time", type="integer", example=88)
 *                                 )
 *                             ),
 *                             @OA\Property(property="score_a", type="integer", example=177),
 *                             @OA\Property(property="score_b", type="integer", example=183),
 *                             @OA\Property(property="round", type="integer", example=1),
 *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-23T21:33:41.000000Z"),
 *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-23T21:33:41.000000Z")
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Solicitud incorrecta.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Debe proporcionar al menos un parámetro de búsqueda.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No se encontraron torneos.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="No se encontraron torneos con los criterios proporcionados.")
 *         )
 *     )
 * )
 */

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