<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllocationDetail extends Model
{
    protected $guarded = [];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }
}