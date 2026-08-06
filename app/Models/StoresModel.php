<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoresModel extends Model
{
    protected $table = 'stores';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'jenis_mitra_id',
        'store_name',
        'owner_name',
        'phone_number',
        'address',
        'latitude',
        'longitude',
        // 'qr_token',
        'qr_token_login',
        'qr_token_checkpoint'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->qr_token)) {
                // Generate a unique QR token when creating a new store
                $model->qr_token = \Illuminate\Support\Str::random(40);
            }
        });
    }
}
