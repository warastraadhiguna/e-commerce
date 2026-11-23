<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isManagementUser() && $user->is_active;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function view(User $user, User $record): bool
    {
        return $this->viewAny($user) && (! $record->isDeveloper() || $user->isDeveloper());
    }

    public function update(User $user, User $record): bool
    {
        return $this->view($user, $record);
    }

    public function delete(User $user, User $record): bool
    {
        return $this->view($user, $record) && $user->canDeleteManagementRecords() && $user->id !== $record->id;
    }

    public function deleteAny(User $user): bool
    {
        return $this->viewAny($user) && $user->canDeleteManagementRecords();
    }
}
