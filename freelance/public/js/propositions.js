document.addEventListener('DOMContentLoaded', function() {
    // Ajouter les badges de budget aux cartes
    const cards = document.querySelectorAll('.proposition-card');
    cards.forEach(card => {
        const budgetText = card.querySelector('p:nth-child(2)').textContent;
        const budgetValue = budgetText.match(/\d+/)[0];
        
        const badge = document.createElement('div');
        badge.className = 'budget-badge';
        badge.textContent = budgetValue + ' TND';
        card.appendChild(badge);
    });
    
    // Animation d'entrée des cartes avec délai progressif
    setTimeout(() => {
        const propositionsList = document.querySelector('.propositions-list');
        if (propositionsList) {
            propositionsList.style.opacity = '1';
        }
    }, 300);
});

// Fonction pour afficher le pop-up avec animation
function toggleForm() {
    var form = document.getElementById("proposition-form");
    var overlay = document.getElementById("overlay");
    
    if (form.style.display === "none" || form.style.display === "") {
        // Afficher l'overlay d'abord
        overlay.style.display = "block";
        setTimeout(() => {
            overlay.classList.add('active');
        }, 10);
        
        // Puis afficher le formulaire avec animation
        setTimeout(() => {
            form.style.display = "block";
            setTimeout(() => {
                form.classList.add('active');
            }, 50);
        }, 200);
        
        // Empêcher le scroll sur le body
        document.body.style.overflow = "hidden";
    } else {
        closeForm();
    }
}

// Fonction pour fermer le pop-up avec animation
function closeForm() {
    var form = document.getElementById("proposition-form");
    var overlay = document.getElementById("overlay");
    
    // Animation de fermeture du formulaire
    form.classList.remove('active');
    overlay.classList.remove('active');
    
    // Attendre la fin de l'animation avant de cacher les éléments
    setTimeout(() => {
        form.style.display = "none";
        overlay.style.display = "none";
        document.body.style.overflow = "auto";
    }, 300);
}

// Fermer le pop-up lorsqu'on clique sur l'overlay
document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById("overlay");
    if (overlay) {
        overlay.addEventListener('click', closeForm);
    }
    
    // Empêcher la propagation du clic depuis le formulaire vers l'overlay
    var form = document.getElementById("proposition-form");
    if (form) {
        form.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    }
    
    // Animation au focus des champs de formulaire
    const formFields = document.querySelectorAll('#proposition-form input, #proposition-form textarea');
    formFields.forEach(field => {
        field.addEventListener('focus', function() {
            const label = this.previousElementSibling.previousElementSibling;
            if (label && label.tagName === 'LABEL') {
                label.style.color = 'var(--primary-color)';
            }
        });
        
        field.addEventListener('blur', function() {
            const label = this.previousElementSibling.previousElementSibling;
            if (label && label.tagName === 'LABEL') {
                label.style.color = 'var(--text-color)';
            }
        });
    });
    
    // Validation du formulaire
    const propositionForm = document.querySelector('#proposition-form form');
    if (propositionForm) {
        propositionForm.addEventListener('submit', function(e) {
            const dateCreation = new Date(this.querySelector('[name="date_creation"]').value);
            const dateFin = new Date(this.querySelector('[name="date_fin"]').value);
            
            if (dateFin <= dateCreation) {
                e.preventDefault();
                alert('La date de fin doit être postérieure à la date de création.');
            }
        });
    }
});

// Animation au scroll pour les cartes
window.addEventListener('scroll', function() {
    const cards = document.querySelectorAll('.proposition-card');
    cards.forEach(card => {
        const cardPosition = card.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.3;
        
        if (cardPosition < screenPosition) {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }
    });
});

// Fonction pour afficher/masquer le formulaire de message
function toggleMessageForm(button) {
    // Trouver le formulaire de message associé au bouton
    const card = button.closest('.proposition-card');
    const messageForm = card.querySelector('.message-form');
    
    if (messageForm.style.display === 'none' || messageForm.style.display === '') {
        // Masquer tous les autres formulaires de message d'abord
        document.querySelectorAll('.message-form').forEach(form => {
            form.style.display = 'none';
            form.previousElementSibling.querySelector('.message-btn').classList.remove('active');
        });
        
        // Afficher le formulaire avec animation
        messageForm.style.display = 'block';
        button.classList.add('active');
        
        // Donner le focus au textarea
        setTimeout(() => {
            messageForm.querySelector('textarea').focus();
        }, 300);
    } else {
        // Cacher le formulaire
        messageForm.style.display = 'none';
        button.classList.remove('active');
    }
}

// Gérer l'envoi du message
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.send-message-btn').forEach(button => {
        button.addEventListener('click', function() {
            const messageForm = this.closest('.message-form');
            const textarea = messageForm.querySelector('textarea');
            const message = textarea.value.trim();
            
            if (message) {
                // Ici vous pouvez ajouter le code pour envoyer le message via AJAX
                alert('Message envoyé: ' + message);
                
                // Réinitialiser et cacher le formulaire
                textarea.value = '';
                messageForm.style.display = 'none';
                messageForm.previousElementSibling.querySelector('.message-btn').classList.remove('active');
            } else {
                alert('Veuillez écrire un message avant d\'envoyer.');
            }
        });
    });
});