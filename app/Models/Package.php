<?php

namespace App\Models;

use app\Enums\PackageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => PackageStatus::class,
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function assignedMessenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_messenger_id');
    }

    public function currentCustodian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_custodian_id');
    }

    public function originAgency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'origin_agency_id');
    }

    public function destinationAgency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'destination_agency_id');
    }
    public function packageType(): BelongsTo
    {
        return $this->belongsTo(PackageType::class);
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function custodyHistories(): HasMany
    {
        return $this->hasMany(CustodyHistory::class);
    }

}
