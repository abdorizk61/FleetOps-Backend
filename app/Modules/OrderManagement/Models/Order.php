<?php

/**
 * @file Order.php
 * @description Eloquent Model for the orders table — OrderManagement Module
 * @module OrderManagement
 * @table orders
 *
 * NOTE: order_id is NOT auto-incremented — IDs are assigned externally per DDL.
 *
 * @author Team Leader (Khalid)
 */

namespace App\Modules\OrderManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\AuthIdentity\Models\Customer;
use App\Modules\AuthIdentity\Models\Driver;
use App\Modules\RouteDispatch\Models\RouteStop;
use App\Modules\ReportingAnalytics\Models\CashLedger;

class Order extends Model
{
    use HasFactory;

    protected $table      = 'orders';
    protected $primaryKey = 'order_id';
    protected $keyType    = 'int';
    public $incrementing  = false; // No IDENTITY in DDL — externally assigned

    // DDL has created_at only (no updated_at)
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    /** @var array<string> */
    protected $fillable = [
        'order_id',
        'driver_id',
        'customer_id',
        'status',
        'eta',
        'delivery_time',
        'priority',
        'price',
        'digital_signature',
        'delivery_preference',
        'payment_method',
        'created_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'price'         => 'integer',
        'delivery_time' => 'datetime',
        'created_at'    => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** Customer who placed this order */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    /** Driver assigned to deliver this order */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /** Parcels contained within this order */
    public function parcels()
    {
        return $this->hasMany(Parcel::class, 'order_id', 'order_id');
    }

    /** Route stop(s) associated with this order */
    public function routeStops()
    {
        return $this->hasMany(RouteStop::class, 'order_id', 'order_id');
    }

    /** Cash ledger entries for this order */
    public function cashLedgerEntries()
    {
        return $this->hasMany(CashLedger::class, 'order_id', 'order_id');
    }
}
