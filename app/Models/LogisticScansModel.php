<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogisticScansModel extends Model
{
    protected $table = 'logistic_scans';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'logistic_id',
        'sack_id',
        'barcode',
        'departed_at',
        'received_at',
    ];

    protected $casts = [
        'logistic_id' => 'string',
        'sack_id' => 'string',
    ];
}
