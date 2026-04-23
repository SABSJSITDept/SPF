<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'anchal',
    ];

    // ── Role constants ────────────────────────────────────────
    const ROLE_SUPER_ADMIN      = 'super_admin';
    const ROLE_OPERATOR         = 'operator';
    const ROLE_ANCHAL_OPERATOR  = 'anchal_operator';

    // ── Role helpers ──────────────────────────────────────────
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isOperator(): bool
    {
        return $this->role === self::ROLE_OPERATOR;
    }

    public function isAnchalOperator(): bool
    {
        return $this->role === self::ROLE_ANCHAL_OPERATOR;
    }

    public function canViewAll(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_OPERATOR]);
    }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN     => 'Super Admin',
            self::ROLE_OPERATOR        => 'Operator',
            self::ROLE_ANCHAL_OPERATOR => 'Anchal Operator',
            default                    => 'Unknown',
        };
    }
}

