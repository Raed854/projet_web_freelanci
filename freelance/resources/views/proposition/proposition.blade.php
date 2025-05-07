@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="{{ asset('css/propositions.css') }}">
<div class="propositions-container">
    <h1 class="page-title">Vos Propositions</h1>
    
    @if(Auth::user()->role=="freelancer") 
    <button onclick="toggleForm()" class="show-form-button">
        <span>Faire une proposition</span>
    </button>
    @endif
    <!-- Overlay pour le fond sombre -->
    <div id="overlay" class="overlay"></div>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Rechercher par contenu ou budget...">
        <div class="search-results-counter"></div>
    </div>

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
    
    <div class="propositions-list" id="propositionsList">
        @foreach ($project->propositions as $proposition)
        <div class="proposition-card" data-contenu="{{ $proposition->contenu }}" data-budget="{{ $proposition->budget }}">
                <p><strong>Proposition:</strong> <span class="proposition-contenu">{{ $proposition->contenu }}</span></p>
                <p onclick="startChat({{ auth()->id() }}, {{ $proposition->author_id }})"><strong>Budget:</strong> <span class="proposition-budget">{{ $proposition->budget }}</span> TND</p>
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

    <!-- Pagination controls -->
    <div id="paginationControls" class="pagination-controls">
        <button id="prevPage" class="pagination-button">Précédent</button>
        <span id="pageNumbers" class="page-numbers"></span>
        <button id="nextPage" class="pagination-button">Suivant</button>
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
                <input type="number" id="project_id" name="project_id" value="{{$proposition->project_id}}" style="display: none;">
                <button type="submit">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Configuration de la pagination
    const propositionsPerPage = 2;
    let currentPage = 1;
    let filteredPropositions = Array.from(document.querySelectorAll('.proposition-card'));

    // Fonction pour afficher les propositions
    function displayPropositions() {
        const startIndex = (currentPage - 1) * propositionsPerPage;
        const endIndex = startIndex + propositionsPerPage;
        
        // Masquer toutes les propositions
        document.querySelectorAll('.proposition-card').forEach(card => {
            card.style.display = 'none';
        });
        
        // Afficher seulement les propositions filtrées pour la page courante
        filteredPropositions.slice(startIndex, endIndex).forEach(card => {
            card.style.display = 'block';
        });
        
        // Mettre à jour les contrôles de pagination
        updatePaginationControls();
    }

    // Fonction pour mettre à jour les contrôles de pagination
    function updatePaginationControls() {
        const totalPages = Math.ceil(filteredPropositions.length / propositionsPerPage);
        const pageNumbers = document.getElementById('pageNumbers');
        const prevButton = document.getElementById('prevPage');
        const nextButton = document.getElementById('nextPage');
        
        pageNumbers.textContent = `Page ${currentPage} sur ${totalPages}`;
        
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage === totalPages || totalPages === 0;
    }

    // Fonction de recherche
    function performSearch() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        
        filteredPropositions = Array.from(document.querySelectorAll('.proposition-card')).filter(card => {
            const contenu = card.querySelector('.proposition-contenu').textContent.toLowerCase();
            const budget = card.querySelector('.proposition-budget').textContent;
            
            // Recherche dans le contenu ou dans le budget
            return contenu.includes(searchTerm) || budget.includes(searchTerm);
        });
        
        // Mettre à jour le compteur de résultats
        document.querySelector('.search-results-counter').textContent = 
            `${filteredPropositions.length} résultat(s) trouvé(s)`;
        
        // Revenir à la première page
        currentPage = 1;
        displayPropositions();
    }

    // Écouteurs d'événements
    document.getElementById('searchInput').addEventListener('input', performSearch);
    document.getElementById('prevPage').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            displayPropositions();
        }
    });
    document.getElementById('nextPage').addEventListener('click', () => {
        const totalPages = Math.ceil(filteredPropositions.length / propositionsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayPropositions();
        }
    });

    // Initialisation
    displayPropositions();

    // Vos autres fonctions existantes
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