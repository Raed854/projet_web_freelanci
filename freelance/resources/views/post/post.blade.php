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

        <!-- Dates automatiques -->
        <div class="mb-3">
            <label class="form-label">Date de création :</label>
            <input type="text" class="form-control" value="{{ now()->format('Y-m-d H:i') }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Date de modification :</label>
            <input type="text" class="form-control" value="{{ now()->format('Y-m-d H:i') }}" disabled>
        </div>

        <button type="submit" class="btn btn-primary">Publier</button>
    </form>

    

    <!-- Affichage de posts statiques -->
    <div class="mt-5">
        <h4>Exemples de Posts</h4>

        @php
            $posts = [
                [
                    'titre' => 'Post 1 : Développement Web',
                    'contenu' => 'Ce post parle du développement avec Laravel et Vue.js.',
                    'image' => 'images/post1.jpg',
                    'date' => '2024-12-01',
                ],
                [
                    'titre' => 'Post 2 : UI/UX Design',
                    'contenu' => 'Introduction aux outils Figma, XD et aux bonnes pratiques UI.',
                    'image' => 'images/post2.jpg',
                    'date' => '2024-11-20',
                ],
                [
                    'titre' => 'Post 3 : Rédaction SEO',
                    'contenu' => 'Rédiger pour Google et pour l’utilisateur, les clés du SEO.',
                    'image' => 'images/post3.jpg',
                    'date' => '2024-10-15',
                ],
            ];
        @endphp

        <div class="row">
            @foreach ($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset($post['image']) }}" class="card-img-top" alt="Image du post">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post['titre'] }}</h5>
                            <p class="card-text">{{ $post['contenu'] }}</p>
                        </div>
                        <div class="card-footer text-muted">
                            Créé le {{ \Carbon\Carbon::parse($post['date'])->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
