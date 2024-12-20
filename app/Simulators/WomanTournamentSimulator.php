<?php


namespace App\Simulators;

class WomanTournamentSimulator extends TournamentSimulator
{
    protected function calculateTeamScore($team, $isDoubles)
    {
        return $this->calculateReactionTime($team, $isDoubles);
    }

    protected function calculateReactionTime($team, $isDoubles)
    {
        if (empty($team)) {
            return 0; 
        }
    
        if ($isDoubles && is_array($team) && count($team) >= 2) {
            return array_sum(array_map(function ($player) {
                return intval($player['reaction_time'] ?? 0);
            }, $team));
        }
    
        if (isset($team['reaction_time'])) {
            return intval($team['reaction_time']);
        }
      
        return 0; 
    }
    
    public function prepareTeams()
    {
        return parent::prepareTeams(); 
    }

    public function simulateMatch($teamA, $teamB, $isDoubles)
    {
        return parent::simulateMatch($teamA, $teamB, $isDoubles);
    }
}



