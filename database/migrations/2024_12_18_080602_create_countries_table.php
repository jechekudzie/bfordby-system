<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
            $table->timestamps();
        });

        // Get the path to the SQL file
        $path = database_path('seeders/sql/countries.sql');
        
        // Check if the file exists
        if (file_exists($path)) {
            // Get the SQL content
            $sql = file_get_contents($path);
            
            // Execute the SQL
            DB::unprepared($sql);
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
