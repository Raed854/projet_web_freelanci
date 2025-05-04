document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner les éléments importants du DOM
    const userItems = document.querySelectorAll('.user-item');
    const chatHeader = document.querySelector('.chat-header');
    const chatMessages = document.querySelector('.chat-messages');
    const messageInput = document.querySelector('.chat-footer input');
    const sendButton = document.querySelector('.chat-footer button');
    
    // Faire défiler automatiquement vers le bas pour voir les derniers messages
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Appeler cette fonction au chargement pour voir les derniers messages
    scrollToBottom();
    
    // Gestion du clic sur les utilisateurs
    userItems.forEach(function(userItem) {
        userItem.addEventListener('click', function() {
            // Retirer la classe active de tous les utilisateurs
            userItems.forEach(item => item.classList.remove('active'));
            
            // Ajouter la classe active à l'utilisateur cliqué
            this.classList.add('active');
            
            // Mettre à jour l'en-tête avec le nom de l'utilisateur sélectionné
            const userName = this.textContent.trim();
            chatHeader.textContent = 'Conversation avec ' + userName;
            
            // Simuler le chargement des messages pour cet utilisateur
            // Dans une application réelle, vous feriez une requête AJAX ici
            
            // Exemple simple de simulation de chargement
            chatMessages.innerHTML = ''; // Vider les messages actuels
            
            // Ajouter quelques messages fictifs selon l'utilisateur sélectionné
            if (userName === 'Alice') {
                addMessage('Alice', 'Salut !');
                addMessage('me', 'Coucou Alice ! Comment tu vas ?');
                addMessage('Alice', 'Je vais bien, merci. Et toi ?');
                addMessage('me', 'Ça va super 👍');
                addMessage('Alice', 'Tu es dispo ce weekend ?');
                addMessage('me', 'Oui, samedi ça me va.');
            } else if (userName === 'Bob') {
                addMessage('Bob', 'Hey ! Quoi de neuf ?');
                addMessage('me', 'Pas grand chose, et toi ?');
                addMessage('Bob', 'Je travaille sur un nouveau projet !');
            } else {
                addMessage(userName, 'Bonjour !');
                addMessage('me', 'Salut ' + userName + ' !');
            }
            
            scrollToBottom();
        });
    });
    
    // Fonction pour ajouter un message à la conversation
    function addMessage(from, text) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.classList.add(from === 'me' ? 'sent' : 'received');
        messageDiv.textContent = text;
        chatMessages.appendChild(messageDiv);
    }
    
    // Gestion de l'envoi de messages
    function sendMessage() {
        const messageText = messageInput.value.trim();
        
        if (messageText !== '') {
            // Ajouter le message à la conversation
            addMessage('me', messageText);
            
            // Vider l'input
            messageInput.value = '';
            
            // Faire défiler vers le bas pour voir le nouveau message
            scrollToBottom();
            
            // Simuler une réponse (dans une application réelle, vous attendriez une réponse du serveur)
            setTimeout(function() {
                const activeUser = document.querySelector('.user-item.active').textContent.trim();
                addMessage(activeUser, 'Je viens de recevoir ton message !');
                scrollToBottom();
            }, 1000);
        }
    }
    
    // Écouteur d'événement pour le bouton Envoyer
    sendButton.addEventListener('click', sendMessage);
    
    // Écouteur d'événement pour la touche Entrée dans l'input
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});