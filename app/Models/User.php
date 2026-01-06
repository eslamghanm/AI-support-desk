<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

{
    
}


class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,HasRoles;

public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['super_admin', 'company_admin', 'agent']);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

   
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function isSuperAdmin(): bool
    {
        return $this->hasAnyRole('super_admin');
    }
    public function isCompanyAdmin(): bool
    {
        return $this->hasAnyRole('company_admin');
    }
   
}
