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
    Schema::table('users', function (Blueprint $table) {
        $table->string('reg_no')->nullable()->unique();
        $table->string('phone')->nullable();
        $table->string('role')->default('user');      // user or admin
        $table->string('status')->default('active');  // pending, active, suspended
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['reg_no', 'phone', 'role', 'status']);
    });
}
};
