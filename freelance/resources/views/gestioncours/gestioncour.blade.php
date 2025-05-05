@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/gestioncours.css') }}">

<div class="courses-container">
    <h1 class="page-title">Gestion des Cours</h1>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="courses-list">
        @foreach ($courses as $course)
            <a href="{{ route('courses.show', $course->id) }}" class="course-card-link">
                <div class="course-card">
                    <h2 class="course-title">{{ $course->titre }}</h2>
                    <p class="course-description">{{ $course->description }}</p>
                    <p class="course-date">Date de publication : {{ $course->created_at->format('d/m/Y') }}</p>
                    <div class="course-actions">
                        <a href="{{ route('courses.edit', $course->id) }}" class="edit-button" data-course-id="{{ $course->id }}" data-course-title="{{ $course->titre }}" data-course-description="{{ $course->description }}" data-course-content="{{ $course->contenu }}" data-course-status="{{ $course->status }}">Modifier</a>
        
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button">Supprimer</button>
                        </form>
                    </div>
                </div>
            </a>

        @endforeach
    </div>

    <button id="addCourseButton" class="add-course-button">Add Course</button>

    <div id="addCourseModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="closeModal" class="close-button">&times;</span>
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
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="files">Files</label>
                    <input type="file" name="files[]" id="files" multiple>
                </div>
                <button type="submit" class="create-button">Ajouter un Cours</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addCourseButton = document.getElementById('addCourseButton');
        const addCourseModal = document.getElementById('addCourseModal');
        const closeModal = document.getElementById('closeModal');

        addCourseButton.addEventListener('click', function () {
            addCourseModal.style.display = 'block';
        });

        closeModal.addEventListener('click', function () {
            addCourseModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === addCourseModal) {
                addCourseModal.style.display = 'none';
            }
        });

        const editButtons = document.querySelectorAll('.edit-button');
        const editCourseModal = document.getElementById('editCourseModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const editForm = document.getElementById('editCourseForm');

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

                editForm.action = `/courses/${courseId}`;
                editCourseModal.style.display = 'block';
            });
        });

        closeEditModal.addEventListener('click', function () {
            editCourseModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === editCourseModal) {
                editCourseModal.style.display = 'none';
            }
        });
    });
</script>

<div id="editCourseModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="closeEditModal" class="close-button">&times;</span>
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
                <label for="editStatus">Status</label>
                <select name="status" id="editStatus" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="editFiles">Files</label>
                <input type="file" name="files[]" id="editFiles" multiple>
                <div class="existing-files">
                    @if ($course->files)
                        @foreach (explode(',', $course->files) as $file)
                            <div class="file-item">
                                @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                                    <img src="{{ asset('storage/' . $file) }}" alt="File Image" class="file-image">
                                @else
                                    <a href="{{ asset('storage/' . $file) }}" download>
                                        <i class="file-icon">📄</i> {{ basename($file) }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <button type="submit" class="create-button">Modifier le Cours</button>
        </form>
    </div>
</div>
@endsection
