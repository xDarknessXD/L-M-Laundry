<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('machine_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cycle_type')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->timestamp('machine_started_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('machine_id');
            $table->dropColumn(['cycle_type', 'duration_minutes', 'machine_started_at']);
        });
    }
};
