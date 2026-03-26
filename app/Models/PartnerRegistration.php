<?php

namespace App\Models;

use App\Enums\PartnerRegistrationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'full_name',
    'whatsapp',
    'message',
    'status',
    'admin_notes',
    'responded_at',
    'responded_by',
    'ip_address',
    'user_agent',
])]
class PartnerRegistration extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PartnerRegistrationStatus::class,
            'responded_at' => 'datetime',
        ];
    }

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function isResponded(): bool
    {
        return $this->status === PartnerRegistrationStatus::Responded;
    }
}
