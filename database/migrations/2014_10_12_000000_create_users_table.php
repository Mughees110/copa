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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('passwordD');
            $table->string('surname')->nullable();
            $table->string('username')->nullable();
            $table->string('country')->nullable();
            $table->string('dateOfBirth')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->string('companyName')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('fullName')->nullable();
            $table->string('iban')->nullable();
            $table->string('clubId')->nullable();
            $table->string('employId')->nullable();
            $table->text('file')->nullable();
            $table->text('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
