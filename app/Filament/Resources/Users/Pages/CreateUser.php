<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\PartnerDiscountStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->normalizePartnerFields($data);
    }

    protected function normalizePartnerFields(array $data): array
    {
        if (in_array(($data['role'] ?? null), [UserRole::Developer->value, UserRole::Admin->value, UserRole::SuperAdmin->value], true)) {
            $data['business_name'] = null;
            $data['contact_person'] = null;
            $data['address'] = null;
        }

        if (($data['role'] ?? null) !== UserRole::Mitra->value) {
            $data['purchase_turnover_total'] = 0;
            $data['minimum_turnover_for_discount'] = 0;
            $data['partner_discount_status'] = PartnerDiscountStatus::Pending->value;
            $data['partner_discount_approved_at'] = null;
            $data['partner_discount_approved_by'] = null;
            $data['partner_discount_notes'] = null;

            unset($data['partner_discount_approved_by_name']);

            return $data;
        }

        if (($data['partner_discount_status'] ?? null) === PartnerDiscountStatus::Approved->value) {
            $data['partner_discount_approved_at'] = $data['partner_discount_approved_at'] ?? now();
            $data['partner_discount_approved_by'] = auth()->id();
        } else {
            $data['partner_discount_approved_at'] = null;
            $data['partner_discount_approved_by'] = null;
        }

        unset($data['partner_discount_approved_by_name']);

        return $data;
    }
}
