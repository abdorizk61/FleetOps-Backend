<?php

/**
 * @file CashLedger.php
 * @description Eloquent Model for the cash_ledger table — ReportingAnalytics Module
 * @module ReportingAnalytics
 * @table cash_ledger
 * @author Team Leader (Khalid)
 */

namespace App\Modules\ReportingAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\AuthIdentity\Models\Driver;
use App\Modules\OrderManagement\Models\Order;

class CashLedger extends Model
{
    use HasFactory;

    protected $table      = 'cash_ledger';
    protected $primaryKey = 'transaction_id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    // No standard timestamps in DDL — has transaction_ts
    public $timestamps = false;

    /** @var array<string> */
    protected $fillable = [
        'order_id',
        'driver_id',
        'amount_collected',
        'payment_method',
        'payment_status',
        'transaction_ts',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'amount_collected' => 'float',
        'transaction_ts'   => 'datetime',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeCollected($query)
    {
        return $query->where('payment_status', 'collected');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The driver who collected this payment */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /**
     * The order this transaction is for.
     * NOTE: No FK constraint was defined in the DDL for order_id.
     *       TODO: Add FK constraint to the migration if referential integrity is needed.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
