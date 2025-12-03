<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');

        $dosenUsers = User::where('role', 'dosen')->get();

        foreach ($dosenUsers as $index => $user) {
            Dosen::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => $this->generateNip($index + 1),
                    'email' => $user->email,
                    'alamat' => $faker->address(),
                    'no_hp' => $faker->phoneNumber(),
                ]
            );
        }
    }

    private function generateNip(int $sequence): string
    {
        return sprintf('%010d', $sequence * rand(10000, 99999));
    }
}