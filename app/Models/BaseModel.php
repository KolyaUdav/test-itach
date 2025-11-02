<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const FIELD_ID = 'id';

    public static function getById(int $itemId): ?static
    {
        return static::query()->find($itemId);
    }
}