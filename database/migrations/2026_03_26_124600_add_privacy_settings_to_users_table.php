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
            $table->boolean('privacy_profile_visible')->default(false);
            $table->boolean('privacy_data_analytics')->default(true);
            $table->boolean('privacy_two_factor')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'privacy_profile_visible',
                'privacy_data_analytics',
                'privacy_two_factor',
            ]);
        });
    }
};
