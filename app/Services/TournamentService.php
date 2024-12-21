<?php
namespace App\Services;

use App\Models\Match;
use App\Simulators\ManTournamentSimulator;
use App\Simulators\WomanTournamentSimulator;
use Illuminate\Support\Facades\Log; 
class TournamentService
{
   
    public function simulateTournament($players, $isDoubles, $gender, $tournament)
    {
        $tournamentSimulator = $this->getTournamentSimulator($gender, $players, $isDoubles);
    
        $teams = $tournamentSimulator->prepareTeams();
    
        $winner = null;
        $matches = [];
        foreach ($teams as $teamA) {
            foreach ($teams as $teamB) {
                if ($teamA !== $teamB) {
                    try {
                        $matchResult = $tournamentSimulator->simulateMatch($teamA, $teamB, $isDoubles);
    
                        $match = Match::create([
                            'tournament_id' => $tournament->id,
                            'team_a' => json_encode($teamA), 
                            'team_b' => json_encode($teamB),
                            'winner' => json_encode($matchResult['winner']), 
                            'score_a' => $matchResult['score_a'],
                            'score_b' => $matchResult['score_b'],
                        ]);
    
                        $matches[] = $match;
    
                        Log::info('Partido guardado exitosamente', [
                            'match_id' => $match->id,
                            'tournament_id' => $tournament->id,
                            'team_a' => $teamA,
                            'team_b' => $teamB,
                            'winner' => $matchResult['winner'],
                            'score_a' => $matchResult['score_a'],
                            'score_b' => $matchResult['score_b'],
                        ]);
    
                        $winner = $matchResult['winner'];
                    } catch (\Exception $e) {
                        Log::error('Error al procesar el partido', [
                            'team_a' => $teamA,
                            'team_b' => $teamB,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                }
            }
        }
    
        $matchResults = collect($matches)->map(function ($match) {
            return [
                'id' => $match->id,
                'team_a' => json_decode($match->team_a, true), 
                'team_b' => json_decode($match->team_b, true), 
                'winner' => json_decode($match->winner, true), 
                'score_a' => $match->score_a,
                'score_b' => $match->score_b,
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


