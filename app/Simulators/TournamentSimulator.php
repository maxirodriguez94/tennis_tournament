<?php

namespace App\Simulators;

class TournamentSimulator
{
    protected $players;
    protected $isDoubles;
    protected $round;

    public function __construct($players, $isDoubles = false, $round = 1)
    {
        $this->players = $players;
        $this->isDoubles = $isDoubles;
        $this->round = $round;
    }

    protected function advanceRound()
    {
        $this->round++; 
    }

    protected function prepareTeams()
    {
        $playersArray = $this->players->toArray();
        shuffle($playersArray);

        if ($this->isDoubles) {
            if (count($playersArray) % 4 !== 0) {
                throw new \Exception("El número de jugadores debe ser múltiplo de 4 para dobles.");
            }
            return array_chunk($playersArray, 2); 
        }

        if (count($playersArray) % 2 !== 0) {
            throw new \Exception("El número de jugadores debe ser par para individuales.");
        }
        return $playersArray; 
    }

    

    protected function simulateMatch($teamA, $teamB, $isDoubles)
    {
        $scoreA = $this->calculateTeamScore($teamA,$isDoubles);
        $scoreB = $this->calculateTeamScore($teamB,$isDoubles);
        $winner = $this->determineWinner($scoreA, $scoreB, $teamA, $teamB);

        return [
            'id' => uniqid(),
            'team_a' => $teamA,
            'team_b' => $teamB,
            'score_a' => $scoreA,
            'score_b' => $scoreB,
            'winner' => $winner,
        ];
    }

    protected function calculateTeamScore($team, $isDoubles)
    {
        return 0; 
    }

    public function determineWinner($scoreA, $scoreB, $teamA, $teamB)
    {
        if ($scoreA === $scoreB) {
            return rand(0, 1) === 0 ? $teamA : $teamB;
        }

        return $scoreA > $scoreB ? $teamA : $teamB;
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
