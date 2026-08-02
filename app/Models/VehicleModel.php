<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $table = 'vehicle';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id_vehicle',
        'plateNo',
        'vehicleType',
    ];

    protected $casts = [
        'id_vehicle' => 'string',
    ];
}
