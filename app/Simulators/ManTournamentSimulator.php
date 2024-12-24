<?php

namespace App\Simulators;

class ManTournamentSimulator extends TournamentSimulator {
    protected function calculateTeamScore( $team, $isDoubles ) {
        return $this->calculateStrengthAndSpeed( $team, $isDoubles );
    }

    protected function calculateStrengthAndSpeed( $team, $isDoubles ) {
        if ( empty( $team ) ) {
            return 0;
        }

        if ( $isDoubles && is_array( $team ) && count( $team ) >= 2 ) {
            $score = 0;

            foreach ( $team as $player ) {
                if ( isset( $player[ 'strength' ], $player[ 'speed' ] ) ) {
                    $score += intval( $player[ 'strength' ] ) + intval( $player[ 'speed' ] );
                }
            }

            return $score;
        }

        if ( isset( $team[ 'strength' ], $team[ 'speed' ] ) ) {
            return intval( $team[ 'strength' ] ) + intval( $team[ 'speed' ] );
        }

        return 0;
    }

    public function prepareTeams() {
        return parent::prepareTeams();
    }

    public function simulateMatch( $teamA, $teamB, $isDoubles ) {
        return parent::simulateMatch( $teamA, $teamB, $isDoubles );
    }
}

