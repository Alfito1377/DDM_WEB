<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    protected $table = 'returns'; 
    protected $fillable = [
        'return_code',
        'store_id',
        'manager_id',
        'status',
        'reason',
        'notes',
        'proof_image',
    ];

    public function details()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
}