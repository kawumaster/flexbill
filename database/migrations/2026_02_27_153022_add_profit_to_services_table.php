<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('profit_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('profit_value', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['profit_type', 'profit_value']);
        });
    }
};
