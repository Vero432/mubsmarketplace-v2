<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('ratings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
        $table->foreignId('rater_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
        $table->unsignedTinyInteger('stars');          // 1 to 5
        $table->text('comment')->nullable();
        $table->timestamps();

        // one person can rate one listing only once
        $table->unique(['listing_id', 'rater_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
