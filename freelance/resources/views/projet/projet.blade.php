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
                <div class="post-details">
                    <h2 class="post-title">{{ $project->name }}</h2>
                    <p class="post-content">{{ $project->description }}</p>
                    <p class="post-date">Début: {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</p>
                    @if ($project->end_date)
                        <p class="post-date">Fin: {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</p>
                    @endif
                    <p class="post-status">Status: {{ ucfirst($project->status) }}</p>
                    <button onclick="openEditPopup({{ $project }})">Modifier</button>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Popup for editing project -->
<div id="editPopup" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="close" onclick="closeEditPopup()">&times;</span>
        <h2>Modifier le Projet</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="text" id="editName" name="name" placeholder="Nom du projet" required><br>
            <textarea id="editDescription" name="description" placeholder="Description du projet" required></textarea><br>
            <input type="date" id="editStartDate" name="start_date" required><br>
            <input type="date" id="editEndDate" name="end_date"><br>
            <select id="editStatus" name="status" required>
                <option value="planned">Planned</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
            </select><br>
            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<script>
    function openEditPopup(project) {
        document.getElementById('editPopup').style.display = 'block';
        document.getElementById('editForm').action = `/projects/${project.id}`;
        document.getElementById('editName').value = project.name;
        document.getElementById('editDescription').value = project.description;
        document.getElementById('editStartDate').value = project.start_date;
        document.getElementById('editEndDate').value = project.end_date;
        document.getElementById('editStatus').value = project.status;
        document.getElementById('editPopup').style.display = 'block';
    }

    function closeEditPopup() {
        document.getElementById('editPopup').style.display = 'none';
    }
</script>
@endsection
