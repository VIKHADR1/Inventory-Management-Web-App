<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();
            $table->foreignId('service_team_id')
                ->nullable()
                ->constrained('service_teams')
                ->nullOnDelete();
            $table->foreignId('handled_by_employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();
            $table->date('service_date');
            $table->text('address');
            $table->string('customer_name');
            $table->string('order_number');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_entries');
    }
};
