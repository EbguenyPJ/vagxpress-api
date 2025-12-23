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
        Schema::table('tw_ordenes', function (Blueprint $table) {
            //
            $table->dateTime('d_inicio_reparto')->nullable()->after('s_firma');
            $table->dateTime('d_fin_reparto')->nullable()->after('s_firma');
            $table->dateTime('d_inicio_regreso')->nullable()->after('s_firma');
            $table->dateTime('d_fin_regreso')->nullable()->after('s_firma');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tw_ordenes', function (Blueprint $table) {
            //
        });
    }
};
