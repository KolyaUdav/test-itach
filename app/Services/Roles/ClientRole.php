<?php

namespace App\Services\Roles;

class ClientRole extends UserRole
{
    public function canWatchLastOrder(): bool
    {
        return true;
    }

    public function canCreateOrder(): bool
    {
        return true;
    }
}
