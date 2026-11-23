<?php

namespace App\Policies;

use App\Models\CompanyProfile;
use App\Models\User;

class CompanyProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageBranding();
    }

    public function view(User $user, CompanyProfile $profile): bool
    {
        return $user->canManageBranding();
    }

    public function update(User $user, CompanyProfile $profile): bool
    {
        return $user->canManageBranding();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function delete(User $user, CompanyProfile $profile): bool
    {
        return false;
    }
}
