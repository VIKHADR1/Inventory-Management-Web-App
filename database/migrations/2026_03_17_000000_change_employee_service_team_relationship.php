<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add service_team_id to employees table
        Schema::table('employees', function (Blueprint $table): void {
            $table->foreignId('service_team_id')
                ->nullable()
                ->constrained('service_teams')
                ->nullOnDelete();
        });

        // Drop the service_team_members pivot table
        Schema::dropIfExists('service_team_members');
    }

    public function down(): void
    {
        // Recreate service_team_members table
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

        // Remove service_team_id from employees
        Schema::table('employees', function (Blueprint $table): void {
            $table->dropForeign(['service_team_id']);
            $table->dropColumn('service_team_id');
        });
    }
};
