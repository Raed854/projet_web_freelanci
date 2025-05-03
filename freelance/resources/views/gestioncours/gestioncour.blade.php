@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/gestioncours.css') }}">

<div class="courses-container">
    <h1 class="page-title">Gestion des Cours</h1>

    @php
        $cours = [
            [
                'id' => 1,
                'titre' => 'Introduction au Développement Web',
                'description' => 'Apprenez les bases du HTML, CSS et JavaScript.',
                'date' => '2025-01-10'
            ],
            [
                'id' => 2,
                'titre' => 'Laravel pour les Débutants',
                'description' => 'Formation complète pour maîtriser Laravel.',
                'date' => '2025-02-05'
            ],
            [
                'id' => 3,
                'titre' => 'Base de Données avec MySQL',
                'description' => 'Comprendre et manipuler les bases de données relationnelles.',
                'date' => '2025-03-15'
            ]
        ];
    @endphp

    <div class="courses-list">
        @foreach ($cours as $c)
            <div class="course-card">
                <h2 class="course-title">{{ $c['titre'] }}</h2>
                <p class="course-description">{{ $c['description'] }}</p>
                <p class="course-date">Date de publication : {{ \Carbon\Carbon::parse($c['date'])->format('d/m/Y') }}</p>

                <div class="course-actions">
                    <a href="{{ url('cours/edit/' . $c['id']) }}" class="edit-button">Modifier</a>

                    <form action="{{ url('cours/destroy/' . $c['id']) }}" method="POST" class="delete-form">
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
