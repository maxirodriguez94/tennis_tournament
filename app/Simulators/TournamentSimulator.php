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


    protected function calculateStrengthAndSpeed($team)
    {
        return array_sum(array_map(function ($player) {
            return $player['strength'] + $player['speed'];
        }, $team));
    }

}


