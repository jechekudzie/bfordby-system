<?php

namespace Database\Seeders;

use App\Models\Title;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            [
                'name' => 'Mr.',
                'description' => 'Mister - Used for men, regardless of marital status',
            ],
            [
                'name' => 'Mrs.',
                'description' => 'Missus - Used for married women',
            ],
            [
                'name' => 'Ms.',
                'description' => 'Used for women regardless of marital status',
            ],
            [
                'name' => 'Miss',
                'description' => 'Used for unmarried women',
            ],
            [
                'name' => 'Dr.',
                'description' => 'Doctor - Used for those with a doctorate degree',
            ],
            [
                'name' => 'Prof.',
                'description' => 'Professor - Used for academic professors',
            ],
            [
                'name' => 'Rev.',
                'description' => 'Reverend - Used for religious leaders',
            ],
            [
                'name' => 'Sir',
                'description' => 'Used for men with knighthood or as a formal address',
            ],
            [
                'name' => 'Lady',
                'description' => 'Used for women with a title or as a formal address',
            ],
            [
                'name' => 'Capt.',
                'description' => 'Captain - Military or maritime title',
            ],
        ];

        foreach ($titles as $title) {
            Title::create($title);
        }
    }
}
