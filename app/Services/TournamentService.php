<?php
namespace App\Services;

use App\Simulators\ManTournamentSimulator;
use App\Simulators\WomanTournamentSimulator;

class TournamentService
{
    public function simulateTournament($players, $isDoubles, $gender)
    {
        $tournamentSimulator = $this->getTournamentSimulator($gender, $players, $isDoubles);

        $teams = $tournamentSimulator->prepareTeams();

        $winner = null;
        $matches = [];

        foreach ($teams as $teamA) {
            foreach ($teams as $teamB) {
                if ($teamA !== $teamB) {
                    $matchResult = $tournamentSimulator->simulateMatch($teamA, $teamB, $isDoubles);
                    $matches[] = $matchResult;
                    $winner = $matchResult['winner'];
                }
            }
        }

        $matchResults = collect($matches)->map(function ($match) {
            return [
                'id' => $match['id'],
                'team_a' => $match['team_a'],
                'team_b' => $match['team_b'],
                'winner' => $match['winner'],
                'score_a' => $match['score_a'],
                'score_b' => $match['score_b'],
            ];
        });

        return [
            'winner' => $winner,
            'match_results' => $matchResults,
        ];
    }

    private function getTournamentSimulator($gender, $players, $isDoubles)
    {
        if ($gender === 'Masculino') {
            return new ManTournamentSimulator($players, $isDoubles, 1);
        }

        return new WomanTournamentSimulator($players, $isDoubles, 1);
    }
}


