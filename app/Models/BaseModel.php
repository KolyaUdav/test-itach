<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function getById(int $itemId): ?static
    {
        return static::query()->find($itemId);
    }
}