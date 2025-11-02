<?php

namespace App\Models;

use App\Enums\Roles\Entities;
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
    const FIELD_ROLE_ID = 'role_id';    

    protected $guarded = ['id'];

    protected $hidden = [
        self::FIELD_PASSWORD,
    ];

    public static function getUser(string $email): ?self
    {
        return self::where(self::FIELD_EMAIL, $email)->first();
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

    /**
     * Получит роль из реестра зарегистрированных ролей
     */
    public function getRole(): ?Entities
    {
        try {
            $roleId = (int)$this->{self::FIELD_ROLE_ID};

            if ($roleId === 0) {
                return null;
            }

            return Entities::from($roleId);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function casts(): array
    {
        return [
            self::FIELD_PASSWORD => 'hashed',
        ];
    }
}
