@extends('layouts.app') {{-- ou supprimer si pas de layout --}}

@section('content')

@php
    $users = ['Alice', 'Bob', 'Charlie', 'Diana', 'Elias', 'Fatima'];
    $selectedUser = 'Alice';
    $messages = [
        ['from' => 'Alice', 'text' => 'Salut !'],
        ['from' => 'me', 'text' => 'Coucou Alice ! Comment tu vas ?'],
        ['from' => 'Alice', 'text' => 'Je vais bien, merci. Et toi ?'],
        ['from' => 'me', 'text' => 'Ça va super 👍'],
        ['from' => 'Alice', 'text' => 'Tu es dispo ce weekend ?'],
        ['from' => 'me', 'text' => 'Oui, samedi ça me va.'],
    ];
@endphp

<div class="messenger-container">
<link href="{{ asset('css/message.css') }}" rel="stylesheet">
    {{-- Liste des utilisateurs --}}
    <div class="user-list">
        <h4>Utilisateurs</h4>
        @foreach($chats as $chat)
            <div class="user-item">
                {{ $chat }}
            </div>
        @endforeach
    </div>

    {{-- Section de chat --}}
    <div class="chat-section">
        <div class="chat-header">
            Conversation avec {{ $selectedUser }}
        </div>

        <div class="chat-messages">
            @foreach($messages as $message)
                <div class="message {{ $message['from'] === 'me' ? 'sent' : 'received' }}">
                    {{ $message['text'] }}
                </div>
            @endforeach
        </div>

        <div class="chat-footer">
            <input type="text" placeholder="Écrire un message...">
            <button>Envoyer</button>
        </div>
    </div>
</div>
<script src="{{ asset('js/message.js') }}"></script>
@endsection
