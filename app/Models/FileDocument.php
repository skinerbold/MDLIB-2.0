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
        'tamanho_arquivo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
