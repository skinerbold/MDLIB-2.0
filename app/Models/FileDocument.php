<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileDocument extends Model
{
    protected $fillable = [
        'nome_arquivo',
        'tipo',
        'glosa',
        'traducao',
        'user_id',
        'caminho_arquivo',
        'tipo_mime',
        'tamanho_arquivo',
        'extracted_text',
        'extraction_status',
        'extraction_error',
        'pdf_pages',
        'pdf_title'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se o texto foi extraído com sucesso
     */
    public function hasExtractedText()
    {
        return $this->extraction_status === 'success' && !empty($this->extracted_text);
    }

    /**
     * Retorna o texto formatado para exibição
     */
    public function getFormattedText()
    {
        if (!$this->hasExtractedText()) {
            return null;
        }

        return $this->extracted_text;
    }

    /**
     * Retorna estatísticas do texto extraído
     */
    public function getTextStats()
    {
        if (!$this->hasExtractedText()) {
            return null;
        }

        return [
            'word_count' => str_word_count($this->extracted_text),
            'char_count' => strlen($this->extracted_text),
            'sentences' => substr_count($this->extracted_text, '.') + substr_count($this->extracted_text, '!') + substr_count($this->extracted_text, '?')
        ];
    }
}
