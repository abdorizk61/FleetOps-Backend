<?php

/**
 * @file: ProofOfDelivery.php
 * @description: نموذج Eloquent لإثبات التسليم (POD) - Order Management Service (fn13/14)
 * @module: OrderManagement
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\OrderManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProofOfDelivery extends Model
{
    use HasFactory;

    protected $table = 'proof_of_delivery';
    protected $primaryKey = 'pod_id';
    public $incrementing = true;

    protected $fillable = [
        'order_id',
        'driver_id',
        'signature_url',    // Azure Blob Storage URL
        'photo_url',        // Azure Blob Storage URL
        'lat',
        'lng',
        'delivered_at',
        'customer_name',
        'customer_signed',
        'is_safe_drop',     // True if customer was absent
        'notes',
    ];

    protected $casts = [
        'lat'            => 'float',
        'lng'            => 'float',
        'customer_signed' => 'boolean',
        'is_safe_drop'   => 'boolean',
        'delivered_at'   => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
