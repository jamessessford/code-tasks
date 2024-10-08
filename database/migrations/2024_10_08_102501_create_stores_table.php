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
        Schema::create('stores', function (Blueprint $table): void {
            $table->id();

            $table->string('name');
            $table->boolean('open')->default(false);
            $table->tinyInteger('type');
            $table->integer('max_delivery_distance');
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
        Schema::dropIfExists('stores');
    }
};
