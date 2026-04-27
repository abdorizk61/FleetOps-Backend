<?php

/**
 * @file Dispatcher.php
 * @description Eloquent Model for the dispatchers table — AuthIdentity Module
 * @module AuthIdentity
 * @table dispatchers
 * @author Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\RouteDispatch\Models\Route;

class Dispatcher extends Model
{
    use HasFactory;

    protected $table      = 'dispatchers';
    protected $primaryKey = 'dispatcher_id';
    protected $keyType    = 'int';
    public $incrementing  = false; // PK is also a FK — no auto-increment

    // No UpdatedAt in DDL
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    /** @var array<string> */
    protected $fillable = [
        'dispatcher_id',    // Set by application (mirrors user_id)
        'created_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The base user account for this dispatcher */
    public function user()
    {
        return $this->belongsTo(User::class, 'dispatcher_id', 'user_id');
    }

    /** Routes managed by this dispatcher */
    public function routes()
    {
        return $this->hasMany(Route::class, 'dispatcher_id', 'dispatcher_id');
    }
}
