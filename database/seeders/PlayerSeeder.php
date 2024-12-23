<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $players = [
            ['name' => 'Roger Federer', 'skill_level' => 99, 'gender' => 'Masculino', 'strength' => 99, 'speed' => 99, 'reaction_time' => 99],
            ['name' => 'Rafael Nadal', 'skill_level' => 99, 'gender' => 'Masculino', 'strength' => 99, 'speed' => 99, 'reaction_time' => 99],
            ['name' => 'Novak Djokovic', 'skill_level' => 99, 'gender' => 'Masculino', 'strength' => 99, 'speed' => 99, 'reaction_time' => 99],
            ['name' => 'Andy Murray', 'skill_level' => 93, 'gender' => 'Masculino', 'strength' => 82, 'speed' => 86, 'reaction_time' => 95],
            ['name' => 'Stan Wawrinka', 'skill_level' => 91, 'gender' => 'Masculino', 'strength' => 87, 'speed' => 80, 'reaction_time' => 94],
            ['name' => 'Juan Martín del Potro', 'skill_level' => 99, 'gender' => 'Masculino', 'strength' => 99, 'speed' => 99, 'reaction_time' => 99],
            ['name' => 'Alexander Zverev', 'skill_level' => 88, 'gender' => 'Masculino', 'strength' => 85, 'speed' => 84, 'reaction_time' => 90],
            ['name' => 'Daniil Medvedev', 'skill_level' => 94, 'gender' => 'Masculino', 'strength' => 86, 'speed' => 88, 'reaction_time' => 90],
            ['name' => 'Dominic Thiem', 'skill_level' => 92, 'gender' => 'Masculino', 'strength' => 85, 'speed' => 86, 'reaction_time' => 90],
            ['name' => 'David Ferrer', 'skill_level' => 89, 'gender' => 'Masculino', 'strength' => 84, 'speed' => 87, 'reaction_time' => 90],
            ['name' => 'Jo-Wilfried Tsonga', 'skill_level' => 87, 'gender' => 'Masculino', 'strength' => 88, 'speed' => 83, 'reaction_time' => 90],
            ['name' => 'Milos Raonic', 'skill_level' => 86, 'gender' => 'Masculino', 'strength' => 89, 'speed' => 81, 'reaction_time' => 90],
            ['name' => 'Kei Nishikori', 'skill_level' => 85, 'gender' => 'Masculino', 'strength' => 80, 'speed' => 85, 'reaction_time' => 90],
            ['name' => 'Grigor Dimitrov', 'skill_level' => 84, 'gender' => 'Masculino', 'strength' => 82, 'speed' => 83, 'reaction_time' => 90],
            ['name' => 'Gael Monfils', 'skill_level' => 83, 'gender' => 'Masculino', 'strength' => 83, 'speed' => 89, 'reaction_time' => 90],
            ['name' => 'Fernando Verdasco', 'skill_level' => 82, 'gender' => 'Masculino', 'strength' => 84, 'speed' => 82, 'reaction_time' => 90],
            ['name' => 'Tommy Haas', 'skill_level' => 81, 'gender' => 'Masculino', 'strength' => 80, 'speed' => 80, 'reaction_time' => 90],
            ['name' => 'Nick Kyrgios', 'skill_level' => 88, 'gender' => 'Masculino', 'strength' => 86, 'speed' => 85, 'reaction_time' => 90],
            ['name' => 'Richard Gasquet', 'skill_level' => 80, 'gender' => 'Masculino', 'strength' => 81, 'speed' => 79, 'reaction_time' => 90],
            ['name' => 'John Isner', 'skill_level' => 79, 'gender' => 'Masculino', 'strength' => 90, 'speed' => 78, 'reaction_time' => 90],
            ['name' => 'Serena Williams', 'skill_level' => 97, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 95],
            ['name' => 'Venus Williams', 'skill_level' => 90, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 95],
            ['name' => 'Simona Halep', 'skill_level' => 93, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 95],
            ['name' => 'Ashleigh Barty', 'skill_level' => 92, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 91],
            ['name' => 'Maria Sharapova', 'skill_level' => 94, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 89],
            ['name' => 'Angelique Kerber', 'skill_level' => 90, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 87],
            ['name' => 'Petra Kvitová', 'skill_level' => 89, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 86],
            ['name' => 'Victoria Azarenka', 'skill_level' => 91, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 88],
            ['name' => 'Caroline Wozniacki', 'skill_level' => 88, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 85],
            ['name' => 'Sloane Stephens', 'skill_level' => 87, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 84],
            ['name' => 'Elina Svitolina', 'skill_level' => 86, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 83],
            ['name' => 'Garbiñe Muguruza', 'skill_level' => 85, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 82],
            ['name' => 'Karolina Pliskova', 'skill_level' => 84, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 81],
            ['name' => 'Dominika Cibulková', 'skill_level' => 83, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 80],
            ['name' => 'Agnieszka Radwanska', 'skill_level' => 82, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 79],
            ['name' => 'Jelena Ostapenko', 'skill_level' => 81, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 78],
            ['name' => 'Belinda Bencic', 'skill_level' => 80, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 77],
            ['name' => 'Madison Keys', 'skill_level' => 79, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 76],
            ['name' => 'Coco Gauff', 'skill_level' => 78, 'gender' => 'Femenino', 'strength' => 0, 'speed' => 0, 'reaction_time' => 75],
        ];

        foreach ($players as $player) {
            Player::create($player);
        }
    }
}
