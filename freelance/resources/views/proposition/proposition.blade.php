@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="{{ asset('css/propositions.css') }}">
<div class="propositions-container">
    <h1 class="page-title">Vos Propositions</h1>
    
    <!-- Bouton pour afficher le formulaire -->
    <button onclick="toggleForm()" class="show-form-button">
        <span>Faire une proposition</span>
    </button>
    
    <!-- Overlay pour le fond sombre -->
    <div id="overlay" class="overlay"></div>
    
    <!-- Formulaire en pop-up -->
    <div id="proposition-form">
        <button onclick="closeForm()" class="close-form-button">&times;</button>
        <form method="POST" action="{{ route('propositions.store', $project->id) }}">
            @csrf
            <label>Contenu de votre proposition :</label><br>
            <textarea name="contenu" required placeholder="Décrivez votre proposition en détail..."></textarea><br>
            
            <label>Budget proposé (TND) :</label><br>
            <input type="number" name="budget" required placeholder="Montant en dinars tunisiens"><br>
            
            <label>Date de début :</label><br>
            <input type="date" name="date_creation" required><br>
            
            <label>Date de livraison :</label><br>
            <input type="date" name="date_fin" required><br>
            
            <button type="submit">Soumettre ma proposition</button>
        </form>
    </div>
    
    <hr>
    <h2>Propositions pour le projet: {{ $project->name }}</h2>
    
    <div class="propositions-list">
        @foreach ($project->propositions as $proposition)
        <div class="proposition-card" onclick="startChat({{ auth()->id() }}, {{ $proposition->author_id }})">
                <p><strong>Proposition:</strong> {{ $proposition->contenu }}</p>
                <p><strong>Budget:</strong> {{ $proposition->budget }} TND</p>
                <p><strong>Début:</strong> {{ \Carbon\Carbon::parse($proposition->date_creation)->format('d/m/Y') }}</p>
                <p><strong>Livraison:</strong> {{ \Carbon\Carbon::parse($proposition->date_fin)->format('d/m/Y') }}</p>
                
                <div class="card-actions">
                    <button onclick="openEditPopup({{ $proposition }})" class="edit-button">Modifier</button>
                    <form action="{{ route('propositions.destroy', $proposition) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button">Supprimer</button>
                    </form>
                </div>
            </div>
            <form id="chatForm{{ $proposition->id }}" action="{{ route('chat.store', $proposition->author_id) }}" method="POST" style="display: none;">
    @csrf
</form>
        @endforeach
    </div>
</div>

<!-- Popup for editing proposition -->
<div id="editPopup" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="close" onclick="closeEditPopup()">&times;</span>
        <h2>Modifier la Proposition</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <label>Contenu de votre proposition :</label><br>
            <textarea id="editContenu" name="contenu" required></textarea><br>
            
            <label>Budget proposé (TND) :</label><br>
            <input type="number" id="editBudget" name="budget" required><br>
            
            <label>Date de début :</label><br>
            <input type="date" id="editDateCreation" name="date_creation" required><br>
            
            <label>Date de livraison :</label><br>
            <input type="date" id="editDateFin" name="date_fin" required><br>
            
            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<script>
    function startChat(user1, user2) {
    fetch("{{ route('chat.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            user1_id: user1,
            user2_id: user2
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Chat created:", data);
        // Optional: redirect to the chat page
        // window.location.href = `/chat/${data.id}`;
    })
    .catch(error => {
        console.error("Error creating chat:", error);
    });
}
    function openEditPopup(proposition) {
        document.getElementById('editPopup').style.display = 'block';
        document.getElementById('editForm').action = `/propositions/${proposition.id}`;
        document.getElementById('editContenu').value = proposition.contenu;
        document.getElementById('editBudget').value = proposition.budget;
        document.getElementById('editDateCreation').value = proposition.date_creation;
        document.getElementById('editDateFin').value = proposition.date_fin;
    }

    function closeEditPopup() {
        document.getElementById('editPopup').style.display = 'none';
    }
</script>

<!-- Inclure le script JavaScript -->
<script src="{{ asset('js/propositions.js') }}"></script>
@endsection