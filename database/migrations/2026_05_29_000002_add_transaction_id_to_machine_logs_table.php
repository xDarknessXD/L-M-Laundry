<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machine_logs', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('machine_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('transaction_id');
        });
    }
};
