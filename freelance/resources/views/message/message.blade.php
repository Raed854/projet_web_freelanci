@extends('layouts.app')

@section('content')
@php
    // The chat messages will be dynamically fetched from the database
    $selectedUser = '';
@endphp

<div class="messenger-container">
    <link href="{{ asset('css/message.css') }}" rel="stylesheet">
    
    {{-- Liste des utilisateurs --}}
    <div class="user-list">
        <h4>Utilisateurs</h4>
        @foreach($chats as $chat)
            <div class="user-item" onclick="startChat({{ $chat->id }}, '{{ $chat->other_user_full_name }}')">
                {{ $chat->other_user_full_name }}
            </div>
        @endforeach
    </div>

    {{-- Section de chat --}}
    <div class="chat-section">
        <div class="chat-header" id="chat-header">
            Conversation avec {{ $selectedUser }}
        </div>

        <div class="chat-messages" id="chat-messages">
            {{-- Messages will be dynamically loaded here from the database --}}
        </div>

        <div class="chat-footer">
            <input type="text" id="message-input" placeholder="Écrire un message...">
            <button id="send-message-button">Envoyer</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/message.js') }}"></script>

<script>
    let currentChatId = null;

    // Function to start a chat with a user (fetch messages for this chat)
    function startChat(chatId, userName) {
        currentChatId = chatId;
        document.getElementById('chat-header').innerText = 'Conversation avec ' + userName;

        // Clear existing messages
        document.getElementById('chat-messages').innerHTML = '';

        // Fetch messages for this chat
        fetchMessages(currentChatId);
    }

    // Fetch messages using an AJAX call
    function fetchMessages(chatId) {
        fetch(`/chats/${chatId}/messages`)
            .then(response => response.json())
            .then(data => {
                const messagesContainer = document.getElementById('chat-messages');
                messagesContainer.innerHTML = '';  // Clear previous messages

                data.messages.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.classList.add(message.sender_id === {{ auth()->id() }} ? 'sent' : 'received');
                    messageElement.innerText = message.text;

                    messagesContainer.appendChild(messageElement);
                });
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }

    // Send a new message
    document.getElementById('send-message-button').addEventListener('click', function() {
        const messageInput = document.getElementById('message-input');
        const messageText = messageInput.value;

        if (messageText.trim() === '') {
            return;
        }

        // Send message to the backend
        sendMessage(currentChatId, messageText);

        // Clear the input field
        messageInput.value = '';
    });

    // Send the message using AJAX
    function sendMessage(chatId, messageText) {
        fetch(`/chats/${chatId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                text: messageText,
                sender_id: {{ auth()->id() }}
            })
        })
        .then(response => response.json())
        .then(data => {
            // After sending the message, update the chat
            fetchMessages(chatId);
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }
</script>

@endsection
