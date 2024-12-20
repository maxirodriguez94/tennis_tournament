<?php


namespace App\Simulators;

class ManTournamentSimulator extends TournamentSimulator
{
    public function determineWinner(callable $simulateMatch)
    {
        $teams = $this->prepareTeams(); 
    
        while (count($teams) > 1) {
            $nextRound = [];
    
            for ($i = 0; $i < count($teams); $i += 2) {
                if (!isset($teams[$i + 1])) {
                    $nextRound[] = $teams[$i];
                    continue;
                }

                $winner = $simulateMatch($teams[$i], $teams[$i + 1]);
    
                if (!$winner) {
                    throw new \Exception("El simulador no pudo determinar un ganador.");
                }
    
                $nextRound[] = $winner; 
            }
    
            $this->advanceRound(); 
            $teams = $nextRound; 
        }
    
        return $teams[0] ?? null; 
    }
    

    


    
    
}

