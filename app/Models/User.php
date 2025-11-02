<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends BaseModel
{
    use HasApiTokens, HasFactory, Notifiable;

    const FIELD_NAME = 'name';
    const FIELD_EMAIL = 'email';
    const FIELD_PASSWORD = 'password';
    const FIELD_REMEMBER_TOKEN = 'remember_token';

    protected $fillable = [
        self::FIELD_NAME,
        self::FIELD_EMAIL,
        self::FIELD_PASSWORD,
    ];

    protected $hidden = [
        self::FIELD_PASSWORD,
        self::FIELD_REMEMBER_TOKEN,
    ];

    public static function getUser(string $email): ?self
    {
        return User::where(User::FIELD_EMAIL, $email)->first();
    }

    public function getNewToken(): string
    {
        return $this->createToken('api-token')->plainTextToken;
    }

    public function checkPassword(string $password): bool
    {
        $currentPass = $this->{self::FIELD_PASSWORD};

        return Hash::check($password, $currentPass);
    }

    public function deleteToken(): void
    {
        $this->tokens()->delete();
    }

    protected function casts(): array
    {
        return [
            self::FIELD_PASSWORD => 'hashed',
        ];
    }
}
