<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'current_streak')) {
                $table->unsignedInteger('current_streak')->default(0)->after('password');
            }
            if (!Schema::hasColumn('users', 'longest_streak')) {
                $table->unsignedInteger('longest_streak')->default(0)->after('current_streak');
            }
            if (!Schema::hasColumn('users', 'last_activity_date')) {
                $table->date('last_activity_date')->nullable()->after('longest_streak');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_streak', 'longest_streak', 'last_activity_date']);
        });
    }
};
