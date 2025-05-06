@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/gestioncours.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<div class="courses-container">
    <h1 class="page-title">Gestion des Cours</h1>
    <button id="addCourseButton" class="add-course-button">Ajouter un cours</button>
    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="courses-list">
        @foreach ($courses as $course)
            <div class="course-card-container">
                <a href="{{ route('courses.show', $course->id) }}" class="course-card-link">
                    <div class="course-card">
                        <h2 class="course-title">{{ $course->titre }}</h2>
                        <p class="course-description">{{ $course->description }}</p>
                        <p class="course-date">Date de publication : {{ $course->created_at->format('d/m/Y') }}</p>
                        <div class="course-actions">
                            <a href="{{ route('courses.edit', $course->id) }}" class="edit-button" 
                               data-course-id="{{ $course->id }}" 
                               data-course-title="{{ $course->titre }}" 
                               data-course-description="{{ $course->description }}" 
                               data-course-content="{{ $course->contenu }}" 
                               data-course-status="{{ $course->status }}">
                                Modifier
                            </a>
            
                            <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Modal d'ajout de cours -->
    <div id="addCourseModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="closeModal" class="close-button">&times;</span>
            <h2 class="modal-title">Ajouter un nouveau cours</h2>
            <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="add-course-form">
                @csrf
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" id="titre" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="contenu">Contenu</label>
                    <textarea name="contenu" id="contenu" required></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Statut</label>
                    <select name="status" id="status" required>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="files">Fichiers</label>
                    <input type="file" name="files[]" id="files" multiple>
                </div>
                <button type="submit" class="create-button">Ajouter le cours</button>
            </form>
        </div>
    </div>

    <!-- Modal d'édition de cours -->
    <div id="editCourseModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="closeEditModal" class="close-button">&times;</span>
            <h2 class="modal-title">Modifier le cours</h2>
            <form id="editCourseForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="editTitre">Titre</label>
                    <input type="text" name="titre" id="editTitre" required>
                </div>
                <div class="form-group">
                    <label for="editDescription">Description</label>
                    <textarea name="description" id="editDescription" required></textarea>
                </div>
                <div class="form-group">
                    <label for="editContenu">Contenu</label>
                    <textarea name="contenu" id="editContenu" required></textarea>
                </div>
                <div class="form-group">
                    <label for="editStatus">Statut</label>
                    <select name="status" id="editStatus" required>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editFiles">Fichiers</label>
                    <input type="file" name="files[]" id="editFiles" multiple>
                    <div id="existingFiles" class="existing-files">
                        <!-- Les fichiers seront chargés dynamiquement par JavaScript -->
                    </div>
                </div>
                <button type="submit" class="create-button">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Modal d'ajout
    const addCourseButton = document.getElementById('addCourseButton');
    const addCourseModal = document.getElementById('addCourseModal');
    const closeModal = document.getElementById('closeModal');

    addCourseButton.addEventListener('click', function () {
        addCourseModal.style.display = 'block';
        // Ajouter un délai pour l'animation CSS
        setTimeout(() => {
            document.body.style.overflow = 'hidden'; // Empêcher le défilement de la page
        }, 10);
    });

    closeModal.addEventListener('click', function () {
        closeModalFunction(addCourseModal);
    });

    // Modal d'édition
    const editButtons = document.querySelectorAll('.edit-button');
    const editCourseModal = document.getElementById('editCourseModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const editForm = document.getElementById('editCourseForm');
    const existingFiles = document.getElementById('existingFiles');

    editButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const courseId = this.dataset.courseId;
            const courseTitle = this.dataset.courseTitle;
            const courseDescription = this.dataset.courseDescription;
            const courseContent = this.dataset.courseContent;
            const courseStatus = this.dataset.courseStatus;

            document.getElementById('editTitre').value = courseTitle;
            document.getElementById('editDescription').value = courseDescription;
            document.getElementById('editContenu').value = courseContent;
            document.getElementById('editStatus').value = courseStatus;

            // Récupération des fichiers existants via AJAX
            fetch(`/courses/${courseId}/files`)
                .then(response => response.json())
                .then(data => {
                    existingFiles.innerHTML = '';
                    if (data.files && data.files.length > 0) {
                        data.files.forEach(file => {
                            const fileItem = document.createElement('div');
                            fileItem.className = 'file-item';
                            
                            if (/\.(jpg|jpeg|png|gif)$/i.test(file)) {
                                fileItem.innerHTML = `
                                    <img src="/storage/${file}" alt="File Image" class="file-image">
                                    <span>${file.split('/').pop()}</span>
                                `;
                            } else {
                                fileItem.innerHTML = `
                                    <a href="/storage/${file}" download>
                                        <i class="file-icon">📄</i> ${file.split('/').pop()}
                                    </a>
                                `;
                            }
                            existingFiles.appendChild(fileItem);
                        });
                    }
                })
                .catch(error => console.error('Erreur lors du chargement des fichiers:', error));

            editForm.action = `/courses/${courseId}`;
            editCourseModal.style.display = 'block';
            // Ajouter un délai pour l'animation CSS
            setTimeout(() => {
                document.body.style.overflow = 'hidden'; // Empêcher le défilement de la page
            }, 10);
        });
    });

    closeEditModal.addEventListener('click', function () {
        closeModalFunction(editCourseModal);
    });

    // Fonction de fermeture avec animation
    function closeModalFunction(modal) {
        modal.style.opacity = '0';
        document.body.style.overflow = ''; // Restaurer le défilement
        setTimeout(() => {
            modal.style.display = 'none';
            modal.style.opacity = ''; // Réinitialiser l'opacité
        }, 300);
    }

    // Fermeture des modals quand on clique à l'extérieur
    window.addEventListener('click', function (event) {
        if (event.target === addCourseModal) {
            closeModalFunction(addCourseModal);
        }
        if (event.target === editCourseModal) {
            closeModalFunction(editCourseModal);
        }
    });

    // Prévenir la fermeture du modal quand on clique sur le contenu
    const modalContents = document.querySelectorAll('.modal-content');
    modalContents.forEach(content => {
        content.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });

    // Animation pour les messages de succès
    const successMessage = document.querySelector('.success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 500);
        }, 5000);
    }
});
</script>
@endsection