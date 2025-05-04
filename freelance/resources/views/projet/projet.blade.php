@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/post.css') }}">

<div class="posts-container">
    <h1 class="page-title">Projet</h1>

    <!-- Formulaire de création de post -->
    <div class="post-form">
        <h2>Créer un nouveau Proejt</h2>
        
            @csrf
            <input type="text" name="titre" placeholder="Titre du post" required><br>
            <textarea name="contenu" placeholder="Contenu du post" required></textarea><br>
           
            <input type="date" name="date" required><br>
            <button type="submit">Créer le Projet</button>
        </form>
    </div>

    @php
        // Données statiques initiales + ajout via session
        $defaultPosts = [
            [
                'id' => 1,
                'titre' => 'Développement Web Full Stack',
                'contenu' => 'Je suis un développeur full stack avec 5 ans d\'expérience. Contactez-moi pour vos projets Laravel/Vue.js.',
               
                'date' => '2024-12-01'
            ],
            [
                'id' => 2,
                'titre' => 'Design UI/UX Moderne',
                'contenu' => 'Spécialiste en Figma et Adobe XD, je crée des interfaces intuitives et responsives.',
               
                'date' => '2024-11-20'
            ],
            [
                'id' => 3,
                'titre' => 'Rédaction SEO Professionnelle',
                'contenu' => 'Rédacteur freelance spécialisé en SEO. Des textes optimisés pour Google et vos visiteurs.',
                
                'date' => '2024-10-15'
            ]
        ];

        // Ajouter les posts créés par l'utilisateur (stockés temporairement en session)
        $userPosts = session('posts', []);
        $posts = array_merge($defaultPosts, $userPosts);
    @endphp

    <!-- Liste des posts -->
    <div class="posts-list">
        @foreach ($posts as $post)
            <div class="post-card">
               
                <div class="post-details">
                    <h2 class="post-title">{{ $post['titre'] }}</h2>
                    <p class="post-content">{{ $post['contenu'] }}</p>
                    <p class="post-date">Créé le {{ \Carbon\Carbon::parse($post['date'])->format('d/m/Y') }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
