<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    // Memastikan model membaca tabel 'returns' (atau sesuaikan jika nama tabel Anda 'return_table')
    protected $table = 'returns'; 
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
}