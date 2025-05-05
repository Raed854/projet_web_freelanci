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

    <!-- Affichage de posts dynamiques -->
    <div class="mt-5">
        <h4>Liste des Posts</h4>

        <div class="row">
            @foreach ($posts as $post)
                <div class="col-md-4 mb-4">
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
</div>
@endsection
