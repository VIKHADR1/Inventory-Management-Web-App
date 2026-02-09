<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_team_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_team_id')
                ->constrained('service_teams')
                ->cascadeOnDelete();
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->timestamps();

            $table->unique(['service_team_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_team_members');
    }
};
