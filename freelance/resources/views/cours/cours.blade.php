@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/cours.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<div class="courses-container">
    <h1 class="page-title">Gestion des Cours</h1>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Rechercher par titre ou description...">
        <div class="search-active-badge">!</div>
        <div class="search-results-counter"></div>
    </div>

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
    <div id="pagination" class="pagination-controls"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Éléments DOM
        const courses = document.querySelectorAll('.course-card-container');
        const paginationContainer = document.getElementById('pagination');
        const searchInput = document.getElementById('searchInput');
        const searchBadge = document.querySelector('.search-active-badge');
        const searchCounter = document.querySelector('.search-results-counter');
        
        // Configuration
        const coursesPerPage = 3;
        let currentPage = 1;
        let filteredCourses = Array.from(courses);

        // Fonction pour afficher les cours visibles
        function displayVisibleCourses() {
            const startIndex = (currentPage - 1) * coursesPerPage;
            const endIndex = startIndex + coursesPerPage;
            let visibleCount = 0;

            courses.forEach((course, index) => {
                if (filteredCourses.includes(course)) {
                    if (index >= startIndex && index < endIndex) {
                        course.style.display = 'block';
                        visibleCount++;
                    } else {
                        course.style.display = 'none';
                    }
                } else {
                    course.style.display = 'none';
                }
            });

            return visibleCount;
        }

        // Fonction pour mettre à jour la pagination
        function updatePagination() {
            paginationContainer.innerHTML = '';
            const totalPages = Math.ceil(filteredCourses.length / coursesPerPage);

            if (totalPages <= 1) return;

            // Bouton Précédent
            if (currentPage > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.textContent = 'Précédent';
                prevBtn.addEventListener('click', () => {
                    currentPage--;
                    displayVisibleCourses();
                    updatePagination();
                });
                paginationContainer.appendChild(prevBtn);
            }

            // Boutons de page
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                if (i === currentPage) {
                    pageBtn.classList.add('active');
                }
                pageBtn.addEventListener('click', () => {
                    currentPage = i;
                    displayVisibleCourses();
                    updatePagination();
                });
                paginationContainer.appendChild(pageBtn);
            }

            // Bouton Suivant
            if (currentPage < totalPages) {
                const nextBtn = document.createElement('button');
                nextBtn.textContent = 'Suivant';
                nextBtn.addEventListener('click', () => {
                    currentPage++;
                    displayVisibleCourses();
                    updatePagination();
                });
                paginationContainer.appendChild(nextBtn);
            }
        }

        // Fonction de recherche
        function performSearch() {
            const query = searchInput.value.toLowerCase().trim();
            
            if (query.length > 0) {
                filteredCourses = Array.from(courses).filter(course => {
                    const title = course.querySelector('.course-title').textContent.toLowerCase();
                    const description = course.querySelector('.course-description').textContent.toLowerCase();
                    return title.includes(query) || description.includes(query);
                });

                searchBadge.style.display = 'inline-block';
                searchCounter.textContent = `${filteredCourses.length} résultat(s)`;
            } else {
                filteredCourses = Array.from(courses);
                searchBadge.style.display = 'none';
                searchCounter.textContent = '';
            }

            currentPage = 1;
            displayVisibleCourses();
            updatePagination();
        }

        // Événements
        searchInput.addEventListener('input', performSearch);

        // Initialisation
        performSearch(); // Affiche tous les cours au départ
    });

    // Le reste de votre code pour les modals peut rester inchangé
    // ...
</script>
@endsection