<?php

namespace App\Services\Roles;

class ClientRole extends UserRole
{
    public function canWatchLastOrder(): bool
    {
        return false;
    }

    public function canCreateOrder(): bool
    {
        return false;
    }
}
