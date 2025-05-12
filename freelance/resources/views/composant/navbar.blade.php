@extends('layouts.app')

@section('content')
<!-- Ajouter la feuille de style pour la navbar -->
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<!-- Ajouter Font Awesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<nav class="navbar">
    <!-- Partie gauche de la navbar -->
    <div class="navbar-left">
        <!-- Logo Freela-connect -->
       
       
            <span>E-patient</span>
       
    </div>

    <!-- Partie centrale de la navbar -->
    <div class="navbar-center">
        <!-- Formulaire de recherche -->
     
            <input type="text" name="query" placeholder="Rechercher..." class="search-input">
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>


</nav>

@endsection
