<?php
// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Relación con usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Métodos de ayuda para verificar roles
    public function isAdmin(): bool
    {
        return $this->slug === 'admin';
    }

    public function isVendedor(): bool
    {
        return $this->slug === 'vendedor';
    }

    public function isClient(): bool
    {
        return $this->slug === 'client';
    }
}