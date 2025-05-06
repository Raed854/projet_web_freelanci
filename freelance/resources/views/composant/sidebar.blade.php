@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
<!-- Ajouter Font Awesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

<div class="dashboard-container">
    <!-- Bouton Toggle pour mobile -->
    <div class="sidebar-toggle d-md-none">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
    
        
        <ul class="sidebar-menu">
            <!-- Lien vers la gestion des utilisateurs -->
            <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
               
                    <i class="fas fa-users"></i> Projet
              
            </li>
          
            <!-- Lien vers la gestion des posts -->
            <li class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">
              
                    <i class="fas fa-file-alt"></i> Posts
             
            </li>

            <!-- Lien vers la gestion des cours -->
            <li class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">
           
                    <i class="fas fa-book"></i> Cours
            
            </li>
            <li class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">
                
                    <i class="fas fa-book"></i> Message
              
            </li>
            <li class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">
                
                    <i class="fas fa-book"></i> propositions
              
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <span>&copy; {{ date('Y') }} Votre Nom</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <h1>Bienvenue sur votre tableau de bord</h1>
            <p>Sélectionnez une option dans la barre latérale pour commencer.</p>
            
            <!-- Contenu principal ici -->
        </div>
    </div>
</div>

<!-- Script pour toggle sidebar sur mobile -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('sidebar-active');
        });
    });
</script>
@endsection
