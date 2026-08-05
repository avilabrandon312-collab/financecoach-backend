<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'context')) {
                $table->string('context')->default('personal')->after('category_id'); // 'personal' | 'business'
            }
            if (!Schema::hasColumn('expenses', 'business_id')) {
                $table->foreignId('business_id')->nullable()->after('context')->constrained('businesses')->onDelete('set null');
            }
        });

        Schema::table('incomes', function (Blueprint $table) {
            if (!Schema::hasColumn('incomes', 'context')) {
                $table->string('context')->default('personal')->after('category_id');
            }
            if (!Schema::hasColumn('incomes', 'business_id')) {
                $table->foreignId('business_id')->nullable()->after('context')->constrained('businesses')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'business_id')) {
                $table->dropForeign(['business_id']);
                $table->dropColumn('business_id');
            }
            if (Schema::hasColumn('expenses', 'context')) {
                $table->dropColumn('context');
            }
        });

        Schema::table('incomes', function (Blueprint $table) {
            if (Schema::hasColumn('incomes', 'business_id')) {
                $table->dropForeign(['business_id']);
                $table->dropColumn('business_id');
            }
            if (Schema::hasColumn('incomes', 'context')) {
                $table->dropColumn('context');
            }
        });
    }
};
