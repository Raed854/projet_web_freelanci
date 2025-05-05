<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .layout {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #34495e;
        }

        .sidebar-header h3 {
            margin: 0;
        }

        .sidebar-header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #bdc3c7;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .sidebar-menu li i {
            margin-right: 10px;
        }

        .sidebar-menu li.active,
        .sidebar-menu li:hover {
            background-color: #34495e;
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid #34495e;
            font-size: 12px;
            text-align: center;
            color: #bdc3c7;
        }

        main {
            flex: 1;
            padding: 20px;
            background-color: #ecf0f1;
            overflow-y: auto;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="navbar">
    <!-- Partie gauche de la navbar -->
    <div class="navbar-left">
        <span>Freela-connect</span>
    </div>

    <!-- Partie centrale de la navbar -->
    <div class="navbar-center">
        <form action="#" method="GET" style="display: flex;">
            <input type="text" name="query" placeholder="Rechercher..." class="search-input">
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="layout">
    <div class="sidebar">
        <div>
            <div class="sidebar-header">
                <h3>Freelance Admin</h3>
                <p>Tableau de bord</p>
            </div>

            <ul class="sidebar-menu">
                <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Projet
                </li>
                <li class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Posts
                </li>
                <li class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Cours
                </li>
                <li class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> Message
                </li>
                <li class="{{ request()->routeIs('propositions.*') ? 'active' : '' }}">
                    <i class="fas fa-lightbulb"></i> Propositions
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <span>&copy; {{ date('Y') }} Freela Connect</span>
        </div>
    </div>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
