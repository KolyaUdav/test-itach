<?php

namespace App\Enums\Roles;

use App\Services\Roles\UserRole;

enum Entities: int
{
    case Client = 1;
    case Admin = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::Client => 'Клиент',
            self::Admin => 'Администратор',
            default => '',
        };
    }

    /**
     * Регистрирует обработчики ролей
     * @return UserRole - обработчик роли
     */
    public function getHandlerInstance(): UserRole
    {
        return match ($this) {
            self::Admin => new \App\Services\Roles\AdminRole(),
            self::Client => new \App\Services\Roles\ClientRole(),
            default => '',
        };
    }
}
