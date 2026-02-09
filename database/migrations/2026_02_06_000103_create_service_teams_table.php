<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_teams', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->foreignId('leader_employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_teams');
    }
};
