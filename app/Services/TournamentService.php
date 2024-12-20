<?php

namespace App\Services;

use App\Models\Match;
use App\Models\Tournament;
use App\Simulators\ManTournamentSimulator;

class TournamentService
{
    private $matchResults = [];
    private $round = 0;

    public function simulateTournament($players, $isDoubles)
    {
        $tournament = Tournament::create([
            'name' => 'Tournament ' . now()->format('Y-m-d H:i:s'),
            'is_doubles' => $isDoubles,
            'gender' => 'Masculino',
        ]);

        $tournamentSimulator = new ManTournamentSimulator($players, $isDoubles, $this->round);

        $winner = $tournamentSimulator->determineWinner(function ($teamA, $teamB) use ($tournament) {
            $matchResult = $this->simulateMatch($teamA, $teamB);

            Match::create([
                'tournament_id' => $tournament->id,
                'team_a' => implode(', ', $this->getPlayerNames($teamA)) ?? 'Desconocido',
                'team_b' => implode(', ', $this->getPlayerNames($teamB)) ?? 'Desconocido',
                'winner' => implode(', ', $this->getPlayerNames($matchResult['winner'])) ?? 'Desconocido',
                'score_a' => $matchResult['score_a'] ?? 0,
                'score_b' => $matchResult['score_b'] ?? 0,
            ]);

            return $matchResult['winner'];
        });

        $matches = Match::where('tournament_id', $tournament->id)->get();

        if ($matches->isEmpty()) {
            throw new \Exception("No se encontraron partidos para el torneo ID {$tournament->id}");
        }

        $matchResults = $matches->map(function ($match) {
            return [
                'id' => $match->id ?? null,
                'tournament_id' => $match->tournament_id ?? null,
                'team_a' => $match->team_a ?? 'Desconocido',
                'team_b' => $match->team_b ?? 'Desconocido',
                'winner' => $match->winner ?? 'Desconocido',
                'score_a' => $match->score_a ?? 0,
                'score_b' => $match->score_b ?? 0,
            ];
        });

        return [
            'winner' => $this->getWinnerName($winner),
            'match_results' => $matchResults,
        ];
    }

    protected function simulateMatch($teamA, $teamB)
    {

        $scoreA = $this->calculateStrengthAndSpeed($teamA);
        $scoreB = $this->calculateStrengthAndSpeed($teamB);

        $winner = ($scoreA === $scoreB)
            ? (rand(0, 1) === 0 ? $teamA : $teamB)
            : ($scoreA > $scoreB ? $teamA : $teamB);

        return [
            'team_a' => $teamA,
            'team_b' => $teamB,
            'score_a' => $scoreA,
            'score_b' => $scoreB,
            'winner' => $winner,
        ];
    }

    protected function getWinnerName($winner)
    {
        if (is_array($winner)) {
            return implode(', ', $this->getPlayerNames($winner));
        } elseif (is_object($winner)) {
            return $winner->name ?? 'Desconocido';
        }

        return 'Desconocido';
    }

    protected function calculateStrengthAndSpeed($team)
    {
        if (!is_array($team)) {
            return 0;
        }

        if (isset($team['strength']) && isset($team['speed'])) {
            return intval($team['strength']) + intval($team['speed']);
        }

        return array_sum(array_map(function ($player) {
            $strength = isset($player['strength']) ? intval($player['strength']) : 0;
            $speed = isset($player['speed']) ? intval($player['speed']) : 0;
            return $strength + $speed;
        }, $team));
    }

    protected function getPlayerNames($team)
    {
        if (isset($team['name'])) {
            return [$team['name']];
        }

        if (is_array($team) && array_keys($team) === range(0, count($team) - 1)) {
            return array_map(function ($player) {
                return $player['name'] ?? 'Desconocido';
            }, $team);
        }
        if (is_object($team)) {
            return [$team->name ?? 'Desconocido'];
        }

        return ['Desconocido'];
    }
}
