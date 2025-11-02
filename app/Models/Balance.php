<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balance extends BaseModel
{
    const FIELD_AMOUNT = 'amount';
    const FIELD_USER_ID = 'user_id';

    const FIELD_USER = 'user';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, self::FIELD_USER_ID);
    }
}
