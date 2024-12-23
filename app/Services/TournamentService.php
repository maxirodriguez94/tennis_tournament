<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Tournament;
use App\Models\Match;
use Illuminate\Support\Facades\Log;
use App\Simulators\ManTournamentSimulator;
use App\Simulators\WomanTournamentSimulator;
use Illuminate\Database\Eloquent\Collection;

class TournamentService
{
    public function simulateTournament(array $validated): array
{
    $gender = $validated['gender'];
    $type = $validated['type'];
    $numPlayers = $validated['players'];

    $players = $this->getPlayers($gender, $numPlayers);

    $tournament = $this->createTournament($gender, $type);

    $isDoubles = $type === 'doubles';
    $tournamentSimulator = $this->getTournamentSimulator($gender, $players, $isDoubles);
    $teams = $tournamentSimulator->prepareTeams();

    $matches = $this->simulateRounds($teams, $tournamentSimulator, $tournament, $isDoubles);

    $winner = $teams[0] ?? null;

    $matchResults = $this->formatMatchResults($matches);

    return [
        'winner' => $winner,
        'match_results' => $matchResults,
    ];
}

private function getPlayers(string $gender, int $numPlayers)
{
    $players = Player::where('gender', $gender)->take($numPlayers)->get();

    if ($players->count() < 8) {
        throw new \Exception('No hay suficientes jugadores para simular el torneo.');
    }

    return $players;
}

private function createTournament(string $gender, string $type): Tournament
{
    return Tournament::create([
        'name' => 'Tournament ' . now()->format('Y-m-d H:i:s'),
        'is_doubles' => $type === 'doubles',
        'gender' => $gender,
    ]);
}

private function simulateRounds(array &$teams, $tournamentSimulator, Tournament $tournament, bool $isDoubles): array
{
    $matches = [];
    $currentRound = 1;

    while (count($teams) > 1) {
        Log::info("Simulando ronda {$currentRound}");
        $nextRoundTeams = [];

        foreach (array_chunk($teams, 2) as $chunk) {
            $teamA = $chunk[0];
            $teamB = $chunk[1] ?? null; 

            if (!$teamB) {
                $nextRoundTeams[] = $teamA;
                continue;
            }

            $match = $this->simulateMatch($teamA, $teamB, $tournamentSimulator, $tournament, $isDoubles, $currentRound);
            if ($match) {
                $matches[] = $match;
                $nextRoundTeams[] = json_decode($match->winner, true);
            }
        }

        $teams = $nextRoundTeams;
        $currentRound++;
    }

    return $matches;
}

private function simulateMatch($teamA, $teamB, $tournamentSimulator, Tournament $tournament, bool $isDoubles, int $currentRound): ?Match
{
    try {
        $matchResult = $tournamentSimulator->simulateMatch($teamA, $teamB, $isDoubles);

        Log::info('Datos del partido antes de guardar', [
            'tournament_id' => $tournament->id,
            'team_a' => json_encode($teamA),
            'team_b' => json_encode($teamB),
            'winner' => json_encode($matchResult['winner']),
            'score_a' => $matchResult['score_a'],
            'score_b' => $matchResult['score_b'],
            'round' => $currentRound,
        ]);

        return Match::create([
            'tournament_id' => $tournament->id,
            'team_a' => json_encode($teamA),
            'team_b' => json_encode($teamB),
            'winner' => json_encode($matchResult['winner']),
            'score_a' => $matchResult['score_a'],
            'score_b' => $matchResult['score_b'],
            'round' => $currentRound,
        ]);
    } catch (\Exception $e) {
        Log::error("Error al procesar el partido", [
            'team_a' => $teamA,
            'team_b' => $teamB,
            'exception' => $e->getMessage(),
        ]);

        return null;
    }
}

private function formatMatchResults(array $matches): array
{
    return collect($matches)->map(function ($match) {
        return [
            'id' => $match->id,
            'team_a' => json_decode($match->team_a, true),
            'team_b' => json_decode($match->team_b, true),
            'winner' => json_decode($match->winner, true),
            'score_a' => $match->score_a,
            'score_b' => $match->score_b,
            'round' => $match->round,
        ];
    })->toArray();
}

    private function getTournamentSimulator($gender, $players, $isDoubles)
    {
        if ($gender === 'Masculino') {
            return new ManTournamentSimulator($players, $isDoubles, 1);
        }
    
        return new WomanTournamentSimulator($players, $isDoubles, 1);
    }

    public function getTournamentWithMatches(array $filters): Collection
    {
        $tournamentId = $filters['tournament_id'] ?? null;
    
        if ($tournamentId) {
            return $this->getSingleTournament($tournamentId);
        }
    
        return $this->getFilteredTournaments($filters);
    }
    
    private function getSingleTournament(int $tournamentId): Collection
    {
        $tournament = $this->findTournamentById($tournamentId);
    
        if (!$tournament) {
            Log::info("Torneo no encontrado", ['tournament_id' => $tournamentId]);
            return new Collection();
        }
    
        return new Collection([$tournament]);
    }
    
    private function getFilteredTournaments(array $filters): Collection
    {
        $query = Tournament::query();
    
        $this->applyFiltersToQuery($query, $filters);
    
        $tournaments = $query->with('matches')->get();
    
        $this->transformTournamentMatches($tournaments);
    
        return $tournaments;
    }
    
    private function applyFiltersToQuery($query, array $filters): void
    {
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }
    
        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $query->whereBetween('created_at', [$filters['startDate'], $filters['endDate']]);
        } elseif (!empty($filters['startDate'])) {
            $query->where('created_at', '>=', $filters['startDate']);
        } elseif (!empty($filters['endDate'])) {
            $query->where('created_at', '<=', $filters['endDate']);
        }
    }
    
    private function findTournamentById(int $tournamentId): ?Tournament
    {
        Log::info("Buscando torneo", ['tournament_id' => $tournamentId]);
    
        $tournament = Tournament::with('matches')->find($tournamentId);
    
        if ($tournament) {
            Log::info("Torneo encontrado", [
                'tournament_id' => $tournamentId,
                'tournament_name' => $tournament->name,
                'matches_count' => $tournament->matches->count(),
            ]);
    
            $this->transformTournamentMatches(new Collection([$tournament]));
        }
    
        return $tournament;
    }
    
    private function transformTournamentMatches(Collection $tournaments): void
    {
        $tournaments->each(function ($tournament) {
            $tournament->matches = $this->transformMatches($tournament->matches);
        });
    }
    
    private function transformMatches(Collection $matches): Collection
    {
        return $matches->map(function ($match) {
            $match->team_a = json_decode($match->team_a, true);
            $match->team_b = json_decode($match->team_b, true);
            $match->winner = json_decode($match->winner, true);
            return $match;
        });
    }
}