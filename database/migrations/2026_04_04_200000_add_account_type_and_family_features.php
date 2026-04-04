<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_type', 10)->default('parent')->after('bio');
            $table->foreignId('parent_id')->nullable()->after('account_type')
                  ->constrained('users')->nullOnDelete();
            // Allow kid accounts without email
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['account_type', 'parent_id']);
        });
    }
};
