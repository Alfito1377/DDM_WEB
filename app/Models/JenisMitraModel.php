<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisMitraModel extends Model
{
    protected $table = 'jenis_mitra';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'nama_jenis_mitra',
        'deskripsi',
    ];
}
