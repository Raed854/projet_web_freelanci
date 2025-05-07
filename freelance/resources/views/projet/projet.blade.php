@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/projet.css') }}">

<div class="posts-container">
    <h1 class="page-title">Projet</h1>

    @if(Auth::user()->role=="client") 
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
    @endif
    <!-- Liste des projets -->
    <div class="posts-list" id="projects-container">
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

    <!-- Conteneur de pagination -->
    <div id="pagination-container" class="pagination"></div>
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
// Pagination
const projects = Array.from(document.querySelectorAll('.post-card'));
const itemsPerPage = 1;  // Nombre d'éléments par page
let currentPage = 1;

function displayPage(page) {
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentPageProjects = projects.slice(startIndex, endIndex);

    // Vider le conteneur de projets
    const projectsContainer = document.getElementById('projects-container');
    projectsContainer.innerHTML = '';
    currentPageProjects.forEach(project => {
        projectsContainer.appendChild(project);
    });

    // Mettre à jour les boutons de pagination
    const paginationContainer = document.getElementById('pagination-container');
    paginationContainer.innerHTML = '';  // Vider les anciens boutons
    const totalPages = Math.ceil(projects.length / itemsPerPage);
    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.classList.add('page-button');
        button.textContent = i;
        button.onclick = () => {
            currentPage = i;
            displayPage(currentPage);
        };
        paginationContainer.appendChild(button);
    }
}

displayPage(currentPage);

// Popup d'édition
function openEditPopup(project) {
    const popup = document.getElementById('editPopup');
    popup.style.display = 'flex';
    
    setTimeout(() => {
        popup.classList.add('active');
    }, 10);
    
    document.getElementById('editForm').action = `/projects/${project.id}`;
    document.getElementById('editName').value = project.name;
    document.getElementById('editDescription').value = project.description;
    document.getElementById('editStartDate').value = project.start_date;
    document.getElementById('editEndDate').value = project.end_date || '';
    document.getElementById('editStatus').value = project.status;
    
    document.body.style.overflow = 'hidden';
}

function closeEditPopup() {
    const popup = document.getElementById('editPopup');
    popup.classList.remove('active');
    
    setTimeout(() => {
        popup.style.display = 'none';
    }, 300);
    
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('editPopup');
    popup.addEventListener('click', function(event) {
        if (event.target === popup) {
            closeEditPopup();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && popup.style.display === 'flex') {
            closeEditPopup();
        }
    });
});
</script>

@endsection
