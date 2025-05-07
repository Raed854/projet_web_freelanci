@extends('layouts.app') <!-- Adapté à votre layout -->

@section('content')
<link rel="stylesheet" href="{{ asset('css/post.css') }}">

<div class="container mt-4">
    <h2>Créer un nouveau post</h2>

    <!-- Formulaire de création -->
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contenu" class="form-label">Contenu</label>
            <textarea name="contenu" id="contenu" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Publier</button>
    </form>

    <!-- Recherche par titre -->
    <div class="mt-4">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par titre...">
    </div>

    <!-- Affichage de posts dynamiques -->
    <div class="mt-5">
        <h4>Liste des Posts</h4>

        <div class="row" id="postsContainer">
            @foreach ($posts as $post)
                <div class="col-md-4 mb-4 post-card">
                    <div class="card h-100">
                        <img src="{{ asset('/storage/' . $post->image) }}" class="card-img-top" alt="Image du post">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->titre }}</h5>
                            <p class="card-text">{{ $post->contenu }}</p>
                        </div>
                        <div class="card-footer text-muted">
                            Créé le {{ $post->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    <div id="pagination" class="mt-4 d-flex justify-content-center">
        <!-- Pagination buttons will be generated dynamically -->
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const postsContainer = document.getElementById('postsContainer');
        const postCards = Array.from(postsContainer.getElementsByClassName('post-card'));
        const paginationContainer = document.getElementById('pagination');

        const itemsPerPage = 3; // Nombre de posts par page
        let filteredPosts = postCards; // Initially all posts are displayed
        const totalPages = Math.ceil(filteredPosts.length / itemsPerPage);
        let currentPage = 1;

        // Fonction pour afficher les posts d'une page
        function showPage(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            filteredPosts.forEach(post => post.style.display = 'none');
            for (let i = start; i < end; i++) {
                if (filteredPosts[i]) {
                    filteredPosts[i].style.display = 'block';
                }
            }
        }

        // Fonction pour générer les boutons de pagination
        function generatePagination() {
            paginationContainer.innerHTML = '';  // Réinitialise les boutons de pagination

            const totalPages = Math.ceil(filteredPosts.length / itemsPerPage);
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.innerText = i;
                pageButton.classList.add('btn', 'btn-outline-primary', 'mx-1');
                pageButton.addEventListener('click', function () {
                    currentPage = i;
                    showPage(currentPage);
                });
                paginationContainer.appendChild(pageButton);
            }
        }

        // Fonction pour la recherche par titre
        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase().trim();
            filteredPosts = postCards.filter(post => {
                const title = post.querySelector('.card-title').textContent.toLowerCase();
                return title.includes(searchTerm);
            });
            generatePagination();
            showPage(currentPage);
        });

        // Initialiser la pagination et afficher la première page
        generatePagination();
        showPage(currentPage);
    });
</script>

@endsection
