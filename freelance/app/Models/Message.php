<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['contenu', 'sender_id', 'chat_id'];

    // Relation : un message appartient à un utilisateur (expéditeur)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relation : un message appartient à un chat
    public function chat()
    {
        return $this->belongsTo(Chat::class,'chat_id');
    }
}
