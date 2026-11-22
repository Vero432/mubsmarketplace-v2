<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_type')->default('buyer');   // buyer or seller (admins use the "role" column)
            $table->text('status_note')->nullable();            // reason shown to rejected or suspended users
            $table->timestamp('terms_accepted_at')->nullable(); // when the user accepted the terms
        });

        // anyone who already posted a listing becomes a seller
        DB::table('users')
            ->whereIn('id', DB::table('listings')->select('user_id'))
            ->update(['account_type' => 'seller']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'status_note', 'terms_accepted_at']);
        });
    }
};