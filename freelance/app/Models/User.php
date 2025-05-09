<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'photo',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'author_id');
    }

    public function chatsAsUser1()
    {
        return $this->hasMany(Chat::class, 'user1_id');
    }

    public function chatsAsUser2()
    {
        return $this->hasMany(Chat::class, 'user2_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isClient()
    {
        return $this->role === 'client';
    }
    public function isFreelancer()
    {
        return $this->role === 'freelance';
    }
}
