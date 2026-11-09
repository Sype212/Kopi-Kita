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
            $table->string('username')->unique()->after('name');
            $table->string('role')->default('kasir')->after('username'); // kasir, admin
            $table->string('shift')->nullable()->after('role'); // e.g. "08.00-16.00"
            $table->string('branch')->nullable()->after('shift'); // e.g. "Kopi Kita • Bandung"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'shift', 'branch']);
        });
    }
};
