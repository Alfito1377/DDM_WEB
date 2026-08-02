<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriversModel extends Model
{
    protected $table = 'driver';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id_driver',
        'name',
        'phone',
        'status',
        'notes',
    ];

    protected $casts = [
        'id_driver' => 'string',
    ];
}
