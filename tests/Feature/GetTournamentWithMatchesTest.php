<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection; // Aquí se importa la clase
use Tests\TestCase;


class GetTournamentWithMatchesTest extends TestCase
{
    use RefreshDatabase;

    protected $tournamentServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tournamentServiceMock = $this->mock(\App\Services\TournamentService::class);
    }

    public function test_returns_no_tournaments_found_message()
    {
        $this->tournamentServiceMock->shouldReceive('getTournamentWithMatches')
            ->once()
            ->with([
                'tournament_id' => null,
                'gender' => 'Femenino',
                'startDate' => '2024-01-01',
                'endDate' => '2024-01-10',
            ])
            ->andReturn(new EloquentCollection());

        $response = $this->json('GET', '/api/tournaments/with-matches', [
            'gender' => 'Femenino',
            'startDate' => '2024-01-01',
            'endDate' => '2024-01-10',
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'No se encontraron torneos con los criterios proporcionados.',
                 ]);
    }

    public function test_returns_success_with_tournaments_found()
    {
        $mockData = new EloquentCollection([
            [
                'id' => 1,
                'name' => 'Torneo de Prueba',
                'gender' => 'Masculino',
                'startDate' => '2024-01-01',
                'endDate' => '2024-01-10',
            ],
        ]);

        $this->tournamentServiceMock->shouldReceive('getTournamentWithMatches')
            ->once()
            ->with([
                'tournament_id' => null,
                'gender' => 'Masculino',
                'startDate' => '2024-01-01',
                'endDate' => '2024-01-10',
            ])
            ->andReturn($mockData);

        $response = $this->json('GET', '/api/tournaments/with-matches', [
            'gender' => 'Masculino',
            'startDate' => '2024-01-01',
            'endDate' => '2024-01-10',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Torneos encontrados.',
                     'data' => $mockData->toArray(),
                 ]);
    }
}
