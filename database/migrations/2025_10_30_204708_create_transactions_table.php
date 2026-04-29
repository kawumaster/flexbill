<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('transactions', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    // }
    public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        // $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('type'); // e.g. airtime, data, tv, electricity, wallet_funding
        $table->decimal('amount', 12, 2);
        $table->string('status')->default('pending');
        $table->string('reference')->nullable();
        $table->json('details')->nullable();
        $table->timestamps();

        $table->foreign('user_id')->constrained()->onDelete('cascade');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
