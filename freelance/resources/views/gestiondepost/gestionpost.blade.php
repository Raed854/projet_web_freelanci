@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/gestionpost.css') }}">

<div class="posts-container">
    <h1 class="page-title">Posts de Freelances</h1>

    @php
        $posts = [
            [
                'id' => 1,
                'titre' => 'Développement Web Full Stack',
                'contenu' => 'Je suis un développeur full stack avec 5 ans d\'expérience. Contactez-moi pour vos projets Laravel/Vue.js.',
                'image' => 'images/post1.jpg',
                'date' => '2024-12-01'
            ],
            [
                'id' => 2,
                'titre' => 'Design UI/UX Moderne',
                'contenu' => 'Spécialiste en Figma et Adobe XD, je crée des interfaces intuitives et responsives.',
                'image' => 'images/post2.jpg',
                'date' => '2024-11-20'
            ],
            [
                'id' => 3,
                'titre' => 'Rédaction SEO Professionnelle',
                'contenu' => 'Rédacteur freelance spécialisé en SEO. Des textes optimisés pour Google et vos visiteurs.',
                'image' => 'images/post3.jpg',
                'date' => '2024-10-15'
            ]
        ];
    @endphp

    <div class="posts-list">
        @foreach ($posts as $post)
            <div class="post-card">
                <img src="{{ asset($post['image']) }}" alt="Image du post" class="post-image">
                <div class="post-details">
                    <h2 class="post-title">{{ $post['titre'] }}</h2>
                    <p class="post-content">{{ $post['contenu'] }}</p>
                    <p class="post-date">Créé le {{ \Carbon\Carbon::parse($post['date'])->format('d/m/Y') }}</p>

                    
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
