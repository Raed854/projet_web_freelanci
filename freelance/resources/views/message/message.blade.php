@extends('layouts.app')

@section('content')
@php
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
            {{-- Messages will be dynamically loaded here --}}
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

    function startChat(chatId, userName) {
        currentChatId = chatId;
        document.getElementById('chat-header').innerText = 'Conversation avec ' + userName;
        document.getElementById('chat-messages').innerHTML = '';
        fetchMessages(currentChatId);
    }

    function fetchMessages(chatId) {
        fetch(`/chats/${chatId}/messages`)
            .then(response => response.json())
            .then(data => {
                const messagesContainer = document.getElementById('chat-messages');
                messagesContainer.innerHTML = '';

                data.messages.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.classList.add(message.sender_id === {{ auth()->id() }} ? 'sent' : 'received');

                    const textElement = document.createElement('span');
                    textElement.innerText = message.text;
                    messageElement.appendChild(textElement);

                    if (message.sender_id === {{ auth()->id() }}) {
                        console.log(message.id);
                        const editBtn = document.createElement('button');
                        editBtn.innerText = 'Modifier';
                        editBtn.onclick = () => editMessage(message.id, message.text);

                        const deleteBtn = document.createElement('button');
                        deleteBtn.innerText = 'Supprimer';
                        deleteBtn.onclick = () => deleteMessage(message.id);

                        messageElement.appendChild(editBtn);
                        messageElement.appendChild(deleteBtn);
                    }

                    messagesContainer.appendChild(messageElement);
                });
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }

    document.getElementById('send-message-button').addEventListener('click', function () {
        const messageInput = document.getElementById('message-input');
        const messageText = messageInput.value;

        if (messageText.trim() === '') {
            return;
        }

        sendMessage(currentChatId, messageText);
        messageInput.value = '';
    });

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
            fetchMessages(chatId);
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }

    function editMessage(messageId, oldText) {
        const newText = prompt('Modifier le message:', oldText);
        if (newText && newText !== oldText) {
            fetch(`/messages/${messageId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ text: newText })
            })
            .then(response => response.json())
            .then(data => {
                fetchMessages(currentChatId);
            })
            .catch(error => {
                console.error('Erreur lors de la modification du message:', error);
            });
        }
    }

    function deleteMessage(messageId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
            fetch(`/messages/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                fetchMessages(currentChatId);
            })
            .catch(error => {
                console.error('Erreur lors de la suppression du message:', error);
            });
        }
    }
</script>
@endsection
