<?php

namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;
    use App\Models\ContactType;

class ContactTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Mobile Phone', 'description' => 'Mobile phone number', 'created_at' => now()],
            ['name' => 'Home Phone', 'description' => 'Home telephone number', 'created_at' => now()],
            ['name' => 'Email', 'description' => 'Email address', 'created_at' => now()],
            ['name' => 'Work Phone', 'description' => 'Work telephone number', 'created_at' => now()],
            ['name' => 'Emergency Contact', 'description' => 'Emergency contact number', 'created_at' => now()],
        ];

       foreach ($types as $type) {
        ContactType::create($type);
       }
    }
}
