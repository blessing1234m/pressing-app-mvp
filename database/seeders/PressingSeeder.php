<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pressing;

class PressingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Crée un propriétaire de pressing
        $owner = User::create([
            'name' => 'Diallo Pressing',
            'email' => 'diallo@pressing.com',
            'password' => bcrypt('password'),
            'type' => 'owner'
        ]);

        // Crée le pressing
        Pressing::create([
            'owner_id' => $owner->id,
            'name' => 'Pressing Express',
            'address' => '123 Avenue de la Liberté, Dakar',
            'phone' => '+228 77 12 45 67',
            'prices' => [
                'shirt' => 2000,
                'pants' => 3000,
                'dress' => 4000,
                'jacket' => 5000,
                'skirt' => 2500
            ],
            'description' => 'Service de pressing rapide et de qualité'
        ]);
        $owner1 = User::create([
            'name' => 'Dishi Pressing',
            'email' => 'dishi@pressing.com',
            'password' => bcrypt('passdishi'),
            'type' => 'owner'
        ]);

        // Crée le pressing
        Pressing::create([
            'owner_id' => $owner1->id,
            'name' => 'Dishi pressingExpress',
            'address' => 'Tokoin Séminaire, Lomé',
            'phone' => '+228 79 77 05 55',
            'prices' => [
                'shirt' => 2500,
                'pants' => 2000,
                'dress' => 3000,
                'jacket' => 2500,
                'skirt' => 2000
            ],
            'description' => 'Service de pressing rapide et Efficace'
        ]);
    }
}
