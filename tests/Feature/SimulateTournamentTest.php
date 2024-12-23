<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SimulateTournamentTest extends TestCase
{
    use RefreshDatabase;

    public function test_simulate_tournament_successful()
    {
   
        $mockService = \Mockery::mock('App\Services\TournamentService');
  
        $mockService->shouldReceive('simulateTournament')
            ->once()
            ->with([
                'gender' => 'Masculino',
                'type' => 'single',
                'players' => 8,
            ])
            ->andReturn([
                'winner' => 'Player 1',
                'match_results' => [
                    ['match' => 1, 'winner' => 'Player 1', 'loser' => 'Player 2'],
                    ['match' => 2, 'winner' => 'Player 3', 'loser' => 'Player 4'],
                ],
            ]);

        $this->app->instance('App\Services\TournamentService', $mockService);

        $response = $this->postJson('/api/tournaments/simulate', [
            'gender' => 'Masculino',
            'type' => 'single',
            'players' => 8,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'winner' => 'Player 1',
                'match_results' => [
                    ['match' => 1, 'winner' => 'Player 1', 'loser' => 'Player 2'],
                    ['match' => 2, 'winner' => 'Player 3', 'loser' => 'Player 4'],
                ],
            ]);
    }

    public function test_simulate_tournament_validation_error()
    {
        $response = $this->postJson('/api/tournaments/simulate', [
            'gender' => 'Otro', 
            'type' => 'invalid_type', 
            'players' => 6, 
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gender', 'type', 'players']);
    }

    public function test_simulate_tournament_server_error()
    {
        $mockService = \Mockery::mock('App\Services\TournamentService');
        $mockService->shouldReceive('simulateTournament')
            ->once()
            ->andThrow(new \Exception('Error interno del servicio'));

        $this->app->instance('App\Services\TournamentService', $mockService);

        $response = $this->postJson('/api/tournaments/simulate', [
            'gender' => 'Masculino',
            'type' => 'single',
            'players' => 8,
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'Ocurrió un error al simular el torneo.',
                'details' => 'Error interno del servicio',
            ]);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
