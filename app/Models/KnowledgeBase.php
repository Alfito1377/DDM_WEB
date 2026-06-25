<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    protected $table = 'knowledge_bases';
    protected $guarded = [];

    // Relasi untuk mengetahui siapa yang mengunggah dokumen
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}