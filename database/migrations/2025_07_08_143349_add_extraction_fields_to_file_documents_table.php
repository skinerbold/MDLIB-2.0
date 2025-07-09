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
        Schema::table('file_documents', function (Blueprint $table) {
            $table->longText('extracted_text')->nullable()->after('tamanho_arquivo');
            $table->enum('extraction_status', ['pending', 'success', 'failed'])->default('pending')->after('extracted_text');
            $table->text('extraction_error')->nullable()->after('extraction_status');
            $table->string('pdf_pages')->nullable()->after('extraction_error');
            $table->string('pdf_title')->nullable()->after('pdf_pages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_documents', function (Blueprint $table) {
            $table->dropColumn([
                'extracted_text',
                'extraction_status',
                'extraction_error',
                'pdf_pages',
                'pdf_title'
            ]);
        });
    }
};
