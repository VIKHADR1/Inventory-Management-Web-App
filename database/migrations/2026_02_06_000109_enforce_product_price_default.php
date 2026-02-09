<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')
            ->whereNull('price')
            ->orWhere('price', 0)
            ->update(['price' => 0.01]);

        DB::statement('ALTER TABLE products MODIFY price DECIMAL(12,2) NOT NULL DEFAULT 0.01');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products MODIFY price DECIMAL(12,2) NOT NULL DEFAULT 0.00');
    }
};
