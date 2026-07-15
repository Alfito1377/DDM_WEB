<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    protected $table = 'returns'; 
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
}