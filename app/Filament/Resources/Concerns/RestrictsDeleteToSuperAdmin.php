<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Database\Eloquent\Model;

trait RestrictsDeleteToSuperAdmin
{
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->canDeleteManagementRecords() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->canDeleteManagementRecords() ?? false;
    }
}
