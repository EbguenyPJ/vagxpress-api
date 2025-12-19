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
        Schema::table('tc_tipos_evidencias', function (Blueprint $table) {
              // Tipo técnico real del archivo
            $table->string('s_mime_type')->nullable()->after('s_tipo_evidencia');

            // Extensión principal (jpg, pdf, docx, xlsx, etc.)
            $table->string('s_extension', 10)->nullable()->after('s_mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tc_tipos_evidencias', function (Blueprint $table) {
            //
        });
    }
};
