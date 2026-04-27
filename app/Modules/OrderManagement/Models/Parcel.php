<?php

/**
 * @file Parcel.php
 * @description Eloquent Model for the parcels table — OrderManagement Module
 * @module OrderManagement
 * @table parcels
 * @author Team Leader (Khalid)
 */

namespace App\Modules\OrderManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\AuthIdentity\Models\Driver;

class Parcel extends Model
{
    use HasFactory;

    protected $table      = 'parcels';
    protected $primaryKey = 'parcel_id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    // DDL has created_at only (no updated_at)
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    /** @var array<string> */
    protected $fillable = [
        'order_id',
        'driver_id',
        'price',
        'category',
        'qr_code',
        'status',
        'weight',
        'created_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'price'      => 'integer',
        'created_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The order this parcel belongs to */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /** The driver handling this parcel */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }
}
