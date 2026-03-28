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
        $table->boolean('notif_reflection')->default(true);
        $table->boolean('notif_activity')->default(true);
        $table->boolean('notif_goal')->default(true);
        $table->boolean('notif_streak')->default(true);
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'notif_reflection', 'notif_activity',
            'notif_goal', 'notif_streak'
        ]);
    });
}
};
