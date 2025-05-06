@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/cours.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<div class="courses-container">
    <h1 class="page-title">Gestion des Cours</h1>
 
    <div class="courses-list">
        @foreach ($courses as $course)
            <div class="course-card-container">
                <a href="{{ route('courses.show', $course->id) }}" class="course-card-link">
                    <div class="course-card">
                        <h2 class="course-title">{{ $course->titre }}</h2>
                        <p class="course-description">{{ $course->description }}</p>
                        <p class="course-date">Date de publication : {{ $course->created_at->format('d/m/Y') }}</p>
                      
                    </div>
                </a>
            </div>
        @endforeach
    </div>

   

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modal d'ajout
        const addCourseButton = document.getElementById('addCourseButton');
        const addCourseModal = document.getElementById('addCourseModal');
        const closeModal = document.getElementById('closeModal');

        addCourseButton.addEventListener('click', function () {
            addCourseModal.style.display = 'block';
        });

        closeModal.addEventListener('click', function () {
            addCourseModal.style.display = 'none';
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
            });
        });

        closeEditModal.addEventListener('click', function () {
            editCourseModal.style.display = 'none';
        });

        // Fermeture des modals quand on clique à l'extérieur
        window.addEventListener('click', function (event) {
            if (event.target === addCourseModal) {
                addCourseModal.style.display = 'none';
            }
            if (event.target === editCourseModal) {
                editCourseModal.style.display = 'none';
            }
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