@extends('layouts.app')

@section('content')
@php
    $selectedUser = '';
@endphp

<div class="messenger-container">
    <link href="{{ asset('css/message.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            <span id="chat-title">Sélectionnez un utilisateur pour commencer</span>
        </div>

        <div class="chat-messages" id="chat-messages">
            {{-- Messages will be dynamically loaded here --}}
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <p>Commencez une conversation</p>
            </div>
        </div>

        <div class="chat-footer">
            <input type="text" id="message-input" placeholder="Écrire un message..." disabled>
            <button id="send-message-button" disabled>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                <span>Envoyer</span>
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('js/message.js') }}"></script>

<script>
    let currentChatId = null;

    function startChat(chatId, userName) {
        currentChatId = chatId;
        document.getElementById('chat-title').innerText = userName;
        document.getElementById('chat-messages').innerHTML = '';
        
        // Enable input and button
        document.getElementById('message-input').disabled = false;
        document.getElementById('send-message-button').disabled = false;
        
        // Remove empty state if it exists
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            emptyState.remove();
        }
        
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
                    messageElement.dataset.messageId = message.id;

                    const textElement = document.createElement('span');
                    textElement.innerText = message.text;
                    messageElement.appendChild(textElement);

                    if (message.sender_id === {{ auth()->id() }}) {
                        const actionsDiv = document.createElement('div');
                        actionsDiv.classList.add('message-actions');
                        
                        const editBtn = document.createElement('button');
                        editBtn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Modifier';
                        editBtn.title = 'Modifier';
                        editBtn.onclick = () => editMessage(message.id, message.text);

                        const deleteBtn = document.createElement('button');
                        deleteBtn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Supprimer';
                        deleteBtn.title = 'Supprimer';
                        deleteBtn.onclick = () => deleteMessage(message.id);

                        actionsDiv.appendChild(editBtn);
                        actionsDiv.appendChild(deleteBtn);
                        messageElement.appendChild(actionsDiv);
                    }

                    messagesContainer.appendChild(messageElement);
                });
                
                // Scroll to bottom after loading messages
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }

    document.getElementById('send-message-button').addEventListener('click', function () {
        const messageInput = document.getElementById('message-input');
        const messageText = messageInput.value;

        if (messageText.trim() === '' || !currentChatId) {
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

    // Enable Enter key to send messages
    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey && this.value.trim() !== '') {
            e.preventDefault();
            document.getElementById('send-message-button').click();
        }
    });
</script>
@endsection