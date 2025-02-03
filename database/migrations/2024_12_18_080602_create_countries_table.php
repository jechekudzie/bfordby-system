<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Country;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 2)->unique(); // ISO 3166-1 alpha-2
            $table->string('phone_code', 10);
            $table->string('slug')->unique();
            $table->softDeletes();
            $table->timestamps();
        });

        // Get the path to the SQL file
        $path = database_path('seeders/sql/countries.sql');
        
        // Check if the file exists
        if (file_exists($path)) {
            // Get the SQL content and parse it into an array of country data
            $sql = file_get_contents($path);
            preg_match_all("/\('([^']+)', '([^']+)', '([^']+)'\)/", $sql, $matches, PREG_SET_ORDER);
            
            // Create each country using the model to trigger slug generation
            foreach ($matches as $match) {
                Country::create([
                    'name' => $match[1],
                    'code' => $match[2],
                    'phone_code' => $match[3],
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
