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
        Schema::create('file_documents', function (Blueprint $table) {
            $table->id();
            $table->string('nome_arquivo');
            $table->string('tipo');
            $table->text('glosa')->nullable();
            $table->text('traducao')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('caminho_arquivo');
            $table->string('tipo_mime');
            $table->integer('tamanho_arquivo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_documents');
    }
};
