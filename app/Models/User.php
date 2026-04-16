<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'notif_reflection',
        'notif_activity',
        'notif_goal',
        'notif_streak',
        'privacy_profile_visible',
        'privacy_data_analytics',
        'privacy_two_factor',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'notif_reflection'        => 'boolean',
            'notif_activity'          => 'boolean',
            'notif_goal'              => 'boolean',
            'notif_streak'            => 'boolean',
            'privacy_profile_visible' => 'boolean',
            'privacy_data_analytics'  => 'boolean',
            'privacy_two_factor'      => 'boolean',
        ];
    }
}
