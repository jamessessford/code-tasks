<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('postcodes', function (Blueprint $table): void {
            $table->id();

            $table->string('postcode', 10)->unique();
            $table->double('latitude');
            $table->double('longitude');
            $table->index(['latitude', 'longitude']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postcodes');
    }
};
