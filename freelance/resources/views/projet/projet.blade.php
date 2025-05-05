@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/projet.css') }}">

<div class="posts-container">
    <h1 class="page-title">Projet</h1>

    <!-- Formulaire de création de projet -->
    <div class="post-form">
        <h2>Créer un nouveau Projet</h2>
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nom du projet" required><br>
            <textarea name="description" placeholder="Description du projet" required></textarea><br>
            <input type="date" name="start_date" required><br>
            <input type="date" name="end_date"><br>
            <select name="status" required>
                <option value="planned">Planned</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
            </select><br>
            <button type="submit">Créer le Projet</button>
        </form>
    </div>

    <!-- Liste des projets -->
    <div class="posts-list">
        @foreach ($projects as $project)
            <div class="post-card">
                <a href="{{ route('propositions.index', $project->id) }}" class="post-card-link">
                    <div class="post-details">
                        <h2 class="post-title">{{ $project->name }}</h2>
                        <p class="post-content">{{ $project->description }}</p>
                        <p class="post-date">Début: {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</p>
                        @if ($project->end_date)
                            <p class="post-date">Fin: {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</p>
                        @endif
                        <p class="post-status">Status: {{ ucfirst($project->status) }}</p>
                    </div>
                </a>
                <div class="button-group">
                    <button onclick="openEditPopup({{ $project }})" class="edit-button">Modifier</button>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Popup amélioré pour l'édition de projet -->
<div id="editPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeEditPopup()">&times;</span>
        <h2>Modifier le Projet</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <input type="text" id="editName" name="name" placeholder="Nom du projet" required>
            </div>
            <div class="form-group">
                <textarea id="editDescription" name="description" placeholder="Description du projet" required></textarea>
            </div>
            <div class="form-group">
                <label for="editStartDate">Date de début</label>
                <input type="date" id="editStartDate" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="editEndDate">Date de fin (optionnelle)</label>
                <input type="date" id="editEndDate" name="end_date">
            </div>
            <div class="form-group">
                <label for="editStatus">Statut</label>
                <select id="editStatus" name="status" required>
                    <option value="planned">Planifié</option>
                    <option value="ongoing">En cours</option>
                    <option value="completed">Terminé</option>
                </select>
            </div>
            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<script>
// Script amélioré pour gérer le popup d'édition
function openEditPopup(project) {
    // Récupérer le popup et ajouter la classe active
    const popup = document.getElementById('editPopup');
    popup.style.display = 'flex';
    
    // Ajouter une classe active après un court délai pour l'animation
    setTimeout(() => {
        popup.classList.add('active');
    }, 10);
    
    // Configurer le formulaire avec les données du projet
    document.getElementById('editForm').action = `/projects/${project.id}`;
    document.getElementById('editName').value = project.name;
    document.getElementById('editDescription').value = project.description;
    document.getElementById('editStartDate').value = project.start_date;
    document.getElementById('editEndDate').value = project.end_date || '';
    document.getElementById('editStatus').value = project.status;
    
    // Empêcher le défilement du corps
    document.body.style.overflow = 'hidden';
}

function closeEditPopup() {
    // Récupérer le popup et supprimer la classe active
    const popup = document.getElementById('editPopup');
    popup.classList.remove('active');
    
    // Cacher le popup après la fin de l'animation
    setTimeout(() => {
        popup.style.display = 'none';
    }, 300);
    
    // Réactiver le défilement du corps
    document.body.style.overflow = '';
}

// Fermer le popup en cliquant à l'extérieur du contenu
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('editPopup');
    
    popup.addEventListener('click', function(event) {
        if (event.target === popup) {
            closeEditPopup();
        }
    });
    
    // Ajouter un gestionnaire d'événements pour la touche Echap
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && popup.style.display === 'flex') {
            closeEditPopup();
        }
    });
});
</script>
@endsection
