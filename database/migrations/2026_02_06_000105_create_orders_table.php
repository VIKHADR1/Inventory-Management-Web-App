<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();
            $table->string('status')->default('processing');
            $table->date('order_date');
            $table->string('order_by')->nullable();
            $table->boolean('service_required')->default(false);
            $table->string('service_status')->default('not_required');
            $table->foreignId('service_team_id')
                ->nullable()
                ->constrained('service_teams')
                ->nullOnDelete();
            $table->foreignId('handled_by_employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
