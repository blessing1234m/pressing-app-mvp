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
            'prices' => json_encode([
                'shirt' => 2000,
                'pants' => 3000,
                'dress' => 4000,
                'jacket' => 5000,
                'skirt' => 2500
            ]),
            'description' => 'Service de pressing rapide et de qualité'
        ]);
    }
}
