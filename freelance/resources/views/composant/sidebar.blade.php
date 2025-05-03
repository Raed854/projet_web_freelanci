@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
<!-- Ajouter Font Awesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="dashboard-container">
    <!-- Bouton Toggle pour mobile -->
    <div class="sidebar-toggle d-md-none">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Freelance Admin</h3>
            <p>Tableau de bord</p>
        </div>
        
        <ul class="sidebar-menu">
          
         
        <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a href="{{ route('') }}">
                    <i class="fas fa-users"></i> Gestion Users
                </a>
            </li>
          
            <li class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">
               
                    <i class="fas fa-file-alt"></i> Gestion Posts
            
            </li>
            <li class="{{ request()->routeIs('cours.*') ? 'active' : '' }}">
                
                    <i class="fas fa-book"></i> Gestion Cours
            
            </li>
            <li class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">
          
                    <i class="fas fa-briefcase"></i> Clients
             
            </li>
            <li class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">
                
                    <i class="fas fa-project-diagram"></i> Projets
           
            </li>
            <li class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
               
                    <i class="fas fa-file-invoice-dollar"></i> Factures
              
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