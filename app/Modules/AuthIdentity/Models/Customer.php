<?php

/**
 * @file Customer.php
 * @description Eloquent Model for the customers table — AuthIdentity Module
 * @module AuthIdentity
 * @table customers
 * @author Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\OrderManagement\Models\Order;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $keyType = 'int';
    public $incrementing = false; // PK is also a FK — no auto-increment

    // No UpdatedAt in DDL
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    /** @var array<string> */
    protected $fillable = [
        'customer_id',      // Set by application (mirrors user_id)
        'address',
        'delivery_preference',
        'created_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The base user account for this customer */
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'user_id');
    }

    /** Orders placed by this customer */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }
}
