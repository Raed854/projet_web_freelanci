@extends('layouts.app')

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
        <div id="searchResultsCount" class="small text-muted mt-1"></div>
    </div>

   <!-- Affichage de posts dynamiques -->
<div class="mt-5">
    <h4>Liste des Posts</h4>

    <div class="row" id="postsContainer">
        @foreach ($posts as $post)
            <div class="col-md-4 mb-4 post-card" data-title="{{ strtolower($post->titre) }}">
                <div class="card h-100">
                    @if($post->image)
                    <img src="{{ asset('/storage/' . $post->image) }}" class="card-img-top" alt="Image du post">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->titre }}</h5>
                        <p class="card-text">{{ $post->contenu }}</p>
                    </div>
                    <div class="card-footer-modern d-flex justify-content-between align-items-center">
    <div class="date-badge">
        <span class="date-icon"><i class="far fa-calendar-alt"></i></span>
        <span class="date-text">{{ $post->created_at->format('d/m/Y') }}</span>
    </div>
    <div class="action-buttons">
        <button onclick class="btn-action btn-edit" data-tooltip="Modifier cet article">
            <i class="fas fa-pen"></i>
            <span>Modifier</span>
        </a>
        <button type="button" class="btn-action btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $post->id }}" data-tooltip="Supprimer cet article">
            <i class="fas fa-trash"></i>
            <span>Supprimer</span>
        </button>
    </div>
</div>

                </div>
            </div>
        @endforeach
    </div>
</div>

    <!-- Pagination -->
    <div id="pagination" class="mt-4 d-flex justify-content-center">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item disabled" id="prevPage">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#" data-page="1">1</a></li>
                <li class="page-item" id="nextPage">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const postsContainer = document.getElementById('postsContainer');
    const postCards = Array.from(document.querySelectorAll('.post-card'));
    const paginationContainer = document.getElementById('pagination');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const searchResultsCount = document.getElementById('searchResultsCount');

    // Configuration
    const itemsPerPage = 3;
    let currentPage = 1;
    let filteredPosts = postCards;

    // Fonction pour afficher les posts
    function displayPosts() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        
        // Masquer tous les posts
        postCards.forEach(post => post.style.display = 'none');
        
        // Afficher les posts filtrés pour la page courante
        filteredPosts.slice(startIndex, endIndex).forEach(post => {
            post.style.display = 'block';
        });
        
        // Mettre à jour le compteur de résultats
        searchResultsCount.textContent = `${filteredPosts.length} résultat(s) trouvé(s)`;
        
        // Mettre à jour la pagination
        updatePagination();
    }

    // Fonction pour mettre à jour la pagination
    function updatePagination() {
        const totalPages = Math.ceil(filteredPosts.length / itemsPerPage);
        const paginationList = paginationContainer.querySelector('.pagination');
        
        // Nettoyer les boutons de page existants (sauf Previous et Next)
        while (paginationList.children.length > 2) {
            paginationList.removeChild(paginationList.children[1]);
        }
        
        // Ajouter les boutons de page
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
            
            const pageLink = document.createElement('a');
            pageLink.className = 'page-link';
            pageLink.href = '#';
            pageLink.textContent = i;
            pageLink.dataset.page = i;
            
            pageLink.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.dataset.page);
                displayPosts();
            });
            
            pageItem.appendChild(pageLink);
            paginationList.insertBefore(pageItem, nextPageBtn);
        }
        
        // Activer/désactiver les boutons Previous/Next
        prevPageBtn.classList.toggle('disabled', currentPage === 1);
        nextPageBtn.classList.toggle('disabled', currentPage === totalPages || totalPages === 0);
    }

    // Gestion des événements de pagination
    prevPageBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            displayPosts();
        }
    });

    nextPageBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const totalPages = Math.ceil(filteredPosts.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayPosts();
        }
    });

    // Fonction de recherche
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        filteredPosts = postCards.filter(post => {
            const title = post.dataset.title;
            return title.includes(searchTerm);
        });
        
        // Revenir à la première page après une recherche
        currentPage = 1;
        displayPosts();
    });

    // Initialisation
    displayPosts();
});

</script>

@endsection