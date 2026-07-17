<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogisticModel extends Model
{
    protected $table = 'logistic';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id_logistic',
        'shipmentId',
        'status',
        'id_mitra',
        'destination',
        'driverId',
        'vehicleId',
        'departedAt',
        'arrivedAt',
    ];

    protected $casts = [
        'id_logistic' => 'string',
        'shipmentId' => 'string',
        'driverId' => 'string',
        'vehicleId' => 'string',
    ];
}
