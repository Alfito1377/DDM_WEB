<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    protected $table = 'return_details';
    protected $guarded = [];

    public function returnOrder()
    {
        return $this->belongsTo(ReturnOrder::class, 'return_id');
    }
}