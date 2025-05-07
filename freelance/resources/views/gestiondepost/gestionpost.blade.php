@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/gestionpost.css') }}">

<div class="posts-container">
    <h1 class="page-title">Posts de Freelances</h1>
    <div class="search-container">
    <input type="text" id="searchInput" placeholder="Rechercher par titre...">
    <div class="search-active-badge">!</div>
    <div class="search-results-counter"></div>
</div>


    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="error-message">
            {{ session('error') }}
        </div>
    @endif

    @php
        use App\Models\Post;
        $posts = Post::all();
    @endphp

    <div class="posts-list">
        @foreach ($posts as $post)
            <div class="post-card">
                <img src="{{ asset('storage/' . $post->image) }}" alt="Image du post" class="post-image">
                <div class="post-details">
                    <h2 class="post-title">{{ $post->titre }}</h2>
                    <p class="post-content">{{ $post->contenu }}</p>
                    <p class="post-created-at">Créé à {{ $post->created_at->format('H:i:s') }}</p>

                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="delete-post-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div id="pagination"></div>

    <div id="addPostModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="closeModal" class="close-button">&times;</span>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" id="titre" required>
                </div>
                <div class="form-group">
                    <label for="contenu">Contenu</label>
                    <textarea name="contenu" id="contenu" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" required>
                </div>
                <div class="form-group">
                    <label for="author_id">Auteur</label>
                    <select name="author_id" id="author_id" required>
                    </select>
                </div>
                <button type="submit" class="create-button">Créer un Post</button>
            </form>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <div id="confirmDeleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <p>Êtes-vous sûr de vouloir supprimer ce post ?</p>
            <button id="confirmDeleteBtn" class="confirm-button">Oui</button>
            <button id="cancelDeleteBtn" class="cancel-button">Annuler</button>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const postsContainer = document.querySelector('.posts-list');
        const posts = Array.from(postsContainer.children);  // Récupère tous les posts
        const itemsPerPage = 3;  // Nombre de posts par page
        const totalPages = Math.ceil(posts.length / itemsPerPage);  // Nombre total de pages
        let currentPage = 1;

        // Fonction pour afficher les posts d'une page
        function showPage(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            // Cache tous les posts
            posts.forEach(post => post.style.display = 'none');

            // Affiche les posts de la page actuelle
            for (let i = start; i < end; i++) {
                if (posts[i]) {
                    posts[i].style.display = 'block';
                }
            }
        }

        // Fonction pour générer les boutons de pagination
        function generatePagination() {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';  // Réinitialise les boutons de pagination

            // Crée un bouton pour chaque page
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.innerText = i;
                pageButton.classList.add('page-btn');
                pageButton.addEventListener('click', function () {
                    currentPage = i;
                    showPage(currentPage);
                });

                paginationContainer.appendChild(pageButton);
            }
        }

        // Initialiser la pagination et afficher la première page
        showPage(currentPage);
        generatePagination();

        // Modal pour ajouter un post
        const addPostButton = document.getElementById('addPostButton');
        const addPostModal = document.getElementById('addPostModal');
        const closeModal = document.getElementById('closeModal');

        if (addPostButton && closeModal) {
            addPostButton.addEventListener('click', function () {
                addPostModal.style.display = 'block';
            });

            closeModal.addEventListener('click', function () {
                addPostModal.style.display = 'none';
            });

            window.addEventListener('click', function (event) {
                if (event.target === addPostModal) {
                    addPostModal.style.display = 'none';
                }
            });
        }

        // Récupère les auteurs
        const authorSelect = document.getElementById('author_id');
        fetch('{{ route('authors.index') }}')
            .then(response => response.json())
            .then(authors => {
                authors.forEach(author => {
                    const option = document.createElement('option');
                    option.value = author.id;
                    option.textContent = `${author.nom} ${author.prenom}`;
                    authorSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching authors:', error));

        // Gestion suppression avec popup
        let formToDelete = null;
        const confirmModal = document.getElementById('confirmDeleteModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = document.getElementById('cancelDeleteBtn');

        document.querySelectorAll('.delete-post-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                formToDelete = form;
                confirmModal.style.display = 'flex';
            });
        });

        confirmBtn.addEventListener('click', function () {
            if (formToDelete) {
                // Crée une requête POST avec fetch pour supprimer
                const formData = new FormData(formToDelete);
                fetch(formToDelete.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        window.location.reload(); // Recharge la page après suppression
                    } else {
                        alert("Erreur lors de la suppression.");
                    }
                });
            }
        });

        cancelBtn.addEventListener('click', function () {
            confirmModal.style.display = 'none';
            formToDelete = null;
        });

        // Fermer en cliquant à l'extérieur
        window.addEventListener('click', function (event) {
            if (event.target === confirmModal) {
                confirmModal.style.display = 'none';
                formToDelete = null;
            }
        });
        
    });
 // Rendre les éléments accessibles globalement
let posts = [];

document.addEventListener('DOMContentLoaded', function () {
    const postsContainer = document.querySelector('.posts-list');
    posts = Array.from(postsContainer.children);

    const itemsPerPage = 3;
    const totalPages = Math.ceil(posts.length / itemsPerPage);
    let currentPage = 1;

    function showPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        posts.forEach(post => post.style.display = 'none');
        for (let i = start; i < end; i++) {
            if (posts[i]) {
                posts[i].style.display = 'block';
            }
        }
    }

    function generatePagination() {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.innerText = i;
            pageButton.classList.add('page-btn');
            pageButton.addEventListener('click', function () {
                currentPage = i;
                showPage(currentPage);
            });
            paginationContainer.appendChild(pageButton);
        }
    }

    showPage(currentPage);
    generatePagination();

    // 🔍 BARRE DE RECHERCHE PAR TITRE
    const searchInput = document.getElementById('searchInput');
    const pagination = document.getElementById('pagination');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let hasSearch = searchTerm !== "";

            posts.forEach(post => {
                const titleElement = post.querySelector('.post-title');
                const title = titleElement ? titleElement.textContent.toLowerCase() : "";
                post.style.display = title.includes(searchTerm) ? 'block' : 'none';
            });

            pagination.style.display = hasSearch ? 'none' : 'block';
            if (!hasSearch) showPage(currentPage);
        });
    }
});

</script>

@endsection
