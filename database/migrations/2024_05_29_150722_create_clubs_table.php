<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('link')->nullable();
            $table->string('image')->nullable();
            $table->longText('images')->nullable();
            $table->string('toggle')->nullable();
            $table->string('userId')->nullable();
            $table->time('openTiming')->nullable();
            $table->longText('offDays')->nullable();
            $table->longText('themeDayText')->nullable();
            $table->longText('themeDayImages')->nullable();
            $table->string('themeDayDate')->nullable();
            $table->string('themeDayToggle')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
