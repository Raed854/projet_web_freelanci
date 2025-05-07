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
        :root {
            --primary-color: #3498db;
            --primary-hover: #2980b9;
            --sidebar-bg: rgb(255, 255, 255);
            --sidebar-text: #333333;
            --sidebar-active: #f1f8fe;
            --sidebar-hover: #e9f5fe;
            --sidebar-border: #e0e0e0;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background-color:rgb(181, 180, 180);
        }

        .layout {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Improved Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-header h3 {
            margin: 0;
            font-weight: 600;
            color: var(--primary-color);
        }

        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--sidebar-text);
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 10px 0;
            margin: 0;
            overflow-y: auto;
        }

        .sidebar-menu li {
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            border-radius: 6px;
            margin: 5px 10px;
            transition: all 0.2s ease;
        }

        .sidebar-menu li i {
            margin-right: 15px;
            font-size: 18px;
            width: 24px;
            text-align: center;
            color: #777;
            transition: color 0.2s ease;
        }

        .sidebar-menu li span {
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }

        .sidebar-menu li.active {
            background-color: var(--sidebar-active);
            font-weight: 600;
        }

        .sidebar-menu li.active i {
            color: var(--primary-color);
        }

        .sidebar-menu li:hover {
            background-color: var(--sidebar-hover);
        }

        .sidebar-menu li:hover i {
            color: var(--primary-color);
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--sidebar-border);
            font-size: 12px;
            text-align: center;
            color: #777;
        }

        /* Sidebar collapsed state */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .sidebar-header h3,
        .sidebar.collapsed .sidebar-menu li span,
        .sidebar.collapsed .sidebar-footer span {
            opacity: 0;
            visibility: hidden;
        }

        .sidebar.collapsed .sidebar-menu li {
            padding: 15px;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-menu li i {
            margin-right: 0;
            font-size: 20px;
        }

        main {
            flex: 1;
            padding: 20px;
            background-color:rgb(244, 244, 244);
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100vh;
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 5;
                display: none;
            }

            .mobile-overlay.active {
                display: block;
            }

            .menu-toggle {
                display: block;
            }
        }

        /* Navbar improvements */
        .navbar {
            background-color: rgb(31, 41, 55);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 10px 20px;
        }

        .navbar-left span {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .search-input {
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            padding: 8px 15px;
            width: 300px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .search-btn {
            background-color: var(--primary-color);
            border: none;
            border-radius: 50%;
            color: white;
            width: 36px;
            height: 36px;
            margin-left: -36px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .search-btn:hover {
            background-color: var(--primary-hover);
        }

        /* Menu item badges */
        .badge {
            background-color: #e74c3c;
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 11px;
            margin-left: auto;
        }
        a {
    text-decoration: none;
    }
    </style>
</head>
<body>

<div class="navbar">
    <!-- Left section of navbar -->
    <div class="navbar-left">
        <button class="menu-toggle" id="menuToggle" style="display: none;">
            <i class="fas fa-bars"></i>
        </button>
        <span>Freela-connect</span>
    </div>

    <!-- Center section of navbar -->
    <div class="navbar-center">
       
    </div>
</div>

<div id="mobileOverlay" class="mobile-overlay"></div>

<div class="layout">
    <div class="sidebar" id="sidebar">
        <div>
            <div class="sidebar-header">
                <h3>Menu</h3>
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>

            <ul class="sidebar-menu">
                <a href="{{ route('projects.index') }}">
                <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i> <span>Projets</span>
                </li>
                </a>
                <a href="{{ route('post.show') }}">
                <li class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> <span>Posts</span>
                </li>
                </a>
                <a href="{{ route('cours.index') }}">
                <li class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> <span>Cours</span>
                </li>
                </a>
                <a href="{{ route('message.index') }}">
                <li class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> <span>Messages</span>
                </li>
                </a>
                <a href="{{ route('login.logout') }}">
                <li class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">
                     <span>Log out</span>
                </li>
                </a>
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
<script>
    // Sidebar toggle functionality
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
        
        // Change icon
        const icon = this.querySelector('i');
        if (sidebar.classList.contains('collapsed')) {
            icon.classList.remove('fa-chevron-left');
            icon.classList.add('fa-chevron-right');
        } else {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-left');
        }
    });

    // Mobile menu functionality
    if (document.getElementById('menuToggle')) {
        document.getElementById('menuToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        });

        document.getElementById('mobileOverlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.remove('mobile-open');
            this.classList.remove('active');
        });
    }

    // Responsive behavior
    function checkWidth() {
        const menuToggle = document.getElementById('menuToggle');
        if (window.innerWidth <= 768) {
            menuToggle.style.display = 'block';
        } else {
            menuToggle.style.display = 'none';
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.getElementById('mobileOverlay').classList.remove('active');
        }
    }

    window.addEventListener('resize', checkWidth);
    checkWidth(); // Initial check
</script>
</body>
</html>