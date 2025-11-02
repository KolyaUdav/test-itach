<?php

namespace App\Services\Roles;

class AdminRole extends UserRole
{
    public function canWatchAllOrders(): bool
    {
        return true;
    }
}
