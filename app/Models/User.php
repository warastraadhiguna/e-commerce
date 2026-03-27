<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\PartnerDiscountStatus;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'phone',
    'business_name',
    'contact_person',
    'address',
    'password',
    'role',
    'is_active',
    'purchase_turnover_total',
    'minimum_turnover_for_discount',
    'partner_discount_status',
    'partner_discount_approved_at',
    'partner_discount_approved_by',
    'partner_discount_notes',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
            'purchase_turnover_total' => 'decimal:2',
            'minimum_turnover_for_discount' => 'decimal:2',
            'partner_discount_status' => PartnerDiscountStatus::class,
            'partner_discount_approved_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'management' && $this->isManagementUser() && $this->is_active;
    }

    public static function normalizePhone(?string $phone): ?string
    {
        $phone = trim((string) $phone);

        if ($phone === '') {
            return null;
        }

        $normalized = preg_replace('/[^\d+]/', '', $phone);

        if (! is_string($normalized) || $normalized === '') {
            return null;
        }

        if (str_starts_with($normalized, '+')) {
            return '+'.preg_replace('/\D/', '', substr($normalized, 1));
        }

        $digits = preg_replace('/\D/', '', $normalized);

        if (! is_string($digits) || $digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            return '+62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return '+'.$digits;
        }

        return $digits;
    }

    public function partnerDiscountApprover(): BelongsTo
    {
        return $this->belongsTo(self::class, 'partner_discount_approved_by');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isManagementUser(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function isBuyer(): bool
    {
        return $this->role === UserRole::Buyer;
    }

    public function isShopper(): bool
    {
        return $this->isBuyer() || $this->isMitra();
    }

    public function isMitra(): bool
    {
        return $this->role === UserRole::Mitra;
    }

    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(ShippingAddress::class)
            ->orderByDesc('is_default')
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest('checked_out_at')->latest('id');
    }

    public function canDeleteManagementRecords(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isEligibleForPartnerPricing(): bool
    {
        if (! $this->isMitra()) {
            return false;
        }

        return $this->partner_discount_status === PartnerDiscountStatus::Approved
            && $this->has_reached_turnover_threshold;
    }

    public function getHasReachedTurnoverThresholdAttribute(): bool
    {
        return (float) $this->purchase_turnover_total >= (float) $this->minimum_turnover_for_discount;
    }

    public function getIsEligibleForDiscountAttribute(): bool
    {
        return $this->isEligibleForPartnerPricing();
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => self::normalizePhone($value),
        );
    }
}
