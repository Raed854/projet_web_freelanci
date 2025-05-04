@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="{{ asset('css/propositions.css') }}">
<div class="propositions-container">
    <h1 class="page-title">Vos Propositions</h1>
    
    <!-- Bouton pour afficher le formulaire -->
    <button onclick="toggleForm()" class="show-form-button">
        <span>Faire une proposition</span>
    </button>
    
    <!-- Overlay pour le fond sombre -->
    <div id="overlay" class="overlay"></div>
    
    <!-- Formulaire en pop-up -->
    <div id="proposition-form">
        <button onclick="closeForm()" class="close-form-button">&times;</button>
        <form method="POST" action="#">
            @csrf
            <label>Contenu de votre proposition :</label><br>
            <textarea name="contenu" required placeholder="Décrivez votre proposition en détail..."></textarea><br>
            
            <label>Budget proposé (TND) :</label><br>
            <input type="number" name="budget" required placeholder="Montant en dinars tunisiens"><br>
            
            <label>Date de début :</label><br>
            <input type="date" name="date_creation" required><br>
            
            <label>Date de livraison :</label><br>
            <input type="date" name="date_fin" required><br>
            
            <button type="submit">Soumettre ma proposition</button>
        </form>
    </div>
    
    <hr>
    <h2>Propositions disponibles</h2>
    
    @php
        $propositions = [
            [
                'contenu' => 'Je peux réaliser ce projet en 7 jours avec un budget de 500 TND. Mon approche sera centrée sur la qualité et la performance.',
                'budget' => 500,
                'date_creation' => '2024-12-01',
                'date_fin' => '2024-12-08'
            ],
            [
                'contenu' => 'Je propose une solution complète pour 750 TND, livraison en 10 jours. Cette offre inclut le support et la maintenance pendant 2 semaines après livraison.',
                'budget' => 750,
                'date_creation' => '2024-12-02',
                'date_fin' => '2024-12-12'
            ],
            [
                'contenu' => 'Développement rapide avec support pendant 1 mois, budget 1000 TND. Je garantis une interface moderne et une expérience utilisateur optimale.',
                'budget' => 1000,
                'date_creation' => '2024-12-03',
                'date_fin' => '2024-12-15'
            ]
        ];
    @endphp
    
    <div class="propositions-list">
        @foreach ($propositions as $proposition)
            <div class="proposition-card">
                <p><strong>Proposition:</strong> {{ $proposition['contenu'] }}</p>
                <p><strong>Budget:</strong> {{ $proposition['budget'] }} TND</p>
                <p><strong>Début:</strong> {{ \Carbon\Carbon::parse($proposition['date_creation'])->format('d/m/Y') }}</p>
                <p><strong>Livraison:</strong> {{ \Carbon\Carbon::parse($proposition['date_fin'])->format('d/m/Y') }}</p>
                <!-- Le badge de budget sera ajouté par JavaScript -->
                
                <div class="card-actions">
                    <button class="message-btn" onclick="toggleMessageForm(this)">Message</button>
                </div>
                
                <!-- Formulaire de message caché -->
                <div class="message-form" style="display: none;">
                    <textarea placeholder="Écrivez votre message ici..."></textarea>
                    <div class="message-actions">
                        <button class="send-message-btn">Envoyer</button>
                        <button class="cancel-message-btn" onclick="toggleMessageForm(this.parentNode.parentNode.previousElementSibling.querySelector('.message-btn'))">Annuler</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Inclure le script JavaScript -->
<script src="{{ asset('js/propositions.js') }}"></script>
@endsection