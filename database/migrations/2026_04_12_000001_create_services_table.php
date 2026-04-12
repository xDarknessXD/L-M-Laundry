<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['regular', 'self_service', 'rush', 'comforter']);
            $table->decimal('price_per_kilo', 10, 2)->default(0);
            $table->decimal('wash_price', 10, 2)->default(0);
            $table->integer('wash_minutes')->default(0);
            $table->decimal('dry_price', 10, 2)->default(0);
            $table->integer('dry_minutes')->default(0);
            $table->decimal('fold_price', 10, 2)->default(0);
            $table->decimal('minimum_kilos', 10, 2)->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
