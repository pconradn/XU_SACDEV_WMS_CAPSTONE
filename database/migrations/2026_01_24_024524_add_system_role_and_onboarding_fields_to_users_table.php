<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('system_role')->nullable()->after('email'); // 'sacdev_admin' or null
            $table->timestamp('password_changed_at')->nullable()->after('password');
            $table->boolean('must_change_password')->default(true)->after('password_changed_at');

            $table->index('system_role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['system_role']);
            $table->dropColumn(['system_role', 'password_changed_at', 'must_change_password']);
        });
    }
};
