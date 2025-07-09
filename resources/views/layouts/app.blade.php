<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MDLIB 2.0</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background: #1e3a8a;
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
        }

        .navbar-custom {
            background: #1e40af;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
        }

        .nav-tabs-custom {
            background: #1e40af;
            border-radius: 0;
            padding: 0.5rem 1rem;
            margin-bottom: 0;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            background: transparent;
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
            border-radius: 0;
            transition: all 0.3s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .nav-tabs-custom .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            font-weight: bold;
        }

        .main-container {
            background: #1e3a8a;
            margin: 0;
            border-radius: 0;
            padding: 2rem;
            box-shadow: none;
            min-height: calc(100vh - 120px);
        }

        .title-section {
            text-align: center;
            padding: 2rem 1rem;
            margin-bottom: 2rem;
        }

        .title-section h1 {
            color: white;
            font-size: 2.2rem;
            font-weight: bold;
            text-shadow: none;
            margin: 0;
            line-height: 1.3;
        }

        .content-area {
            background: transparent;
            border-radius: 0;
            padding: 0;
            min-height: 400px;
        }

        /* Main Boxes Row */
        .main-boxes-row {
            margin-bottom: 2rem;
        }

        /* Main Box Styling */
        .main-box {
            background: white;
            border-radius: 12px;
            border: 3px solid #f59e0b;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            height: 450px;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .box-title {
            color: #1f2937;
            font-weight: bold;
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 1rem;
            line-height: 1.2;
            flex-shrink: 0;
            background: #f59e0b;
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            margin: -0.5rem -0.5rem 1rem -0.5rem;
        }

        .box-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
        }

        .viewer-area {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            color: #6c757d;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);
            border: 2px dashed #e5e7eb;
        }

        /* Mini sections para o terceiro box */
        .mini-section {
            background: rgba(255,255,255,0.8);
            border-radius: 5px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .mini-section h6 {
            color: #1f2937;
            font-weight: bold;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .mini-section:last-child {
            margin-bottom: 0;
        }

        .pdf-container, .glossario-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1rem;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border: 3px solid #f59e0b;
            position: relative;
        }

        .pdf-container h3, .glossario-container h3 {
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .expand-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #f59e0b;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .expand-icon:hover {
            color: #d97706;
            transform: scale(1.1);
        }

        .side-panel {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 3px solid #f59e0b;
            margin-bottom: 1rem;
        }

        .side-panel-unified {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            border: 3px solid #f59e0b;
            margin-bottom: 1rem;
            min-height: 120px;
        }

        .side-panel h5, .side-panel-unified h5 {
            color: #1f2937;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .side-panel-content {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            min-height: 80px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .box-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: auto; /* Empurra os botões para o final do box */
        }

        .box-buttons .btn-custom {
            flex: 1;
            min-width: 80px;
            max-width: 120px;
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
            margin: 1rem 0;
        }

        .btn-custom {
            background: #f59e0b;
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-custom:hover {
            background: #d97706;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-custom:active {
            transform: translateY(0);
            background: #b45309;
        }

        .file-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            border: 3px solid #f59e0b;
        }

        .file-table th {
            background: #f59e0b;
            color: white;
            font-weight: 600;
            padding: 1rem 0.5rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .file-table td {
            padding: 0.75rem 0.5rem;
            text-align: center;
            vertical-align: middle;
            color: #374151;
        }

        .file-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .file-table tbody tr:hover {
            background: #fef3c7;
        }

        .user-icon {
            background: #f59e0b;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-icon:hover {
            background: #d97706;
            transform: scale(1.05);
        }

        /* Ajustes nos botões da tabela */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 4px;
        }

        .btn-danger {
            background: #ef4444;
            border-color: #ef4444;
        }

        .btn-danger:hover {
            background: #dc2626;
            border-color: #dc2626;
        }

        .btn-primary {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .btn-primary:hover {
            background: #2563eb;
            border-color: #2563eb;
        }

        /* Melhorias gerais */
        .main-boxes-row {
            margin-bottom: 3rem;
        }

        .container-fluid {
            padding: 0;
        }

        .user-icon:hover {
            background: linear-gradient(135deg, #4a5c6d 0%, #3a4c5d 100%);
            transform: scale(1.1);
        }

        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .main-boxes-row {
            margin-bottom: 2rem;
            display: flex;
            align-items: stretch;
        }

        .main-box {
            background: linear-gradient(135deg, #b8e6e6 0%, #a8d6d6 100%);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            min-height: 380px;
            display: flex;
            flex-direction: column;
            border: 4px solid #1a252f;
            position: relative;
            box-shadow: 0 12px 35px rgba(26, 37, 47, 0.6);
            /* Contorno/sombra azul escuro mais pronunciada */
            background-clip: padding-box;
        }

        .main-box::before {
            content: '';
            position: absolute;
            top: -6px;
            left: -6px;
            right: -6px;
            bottom: -6px;
            background: linear-gradient(135deg, #1a252f 0%, #2c3e50 50%, #34495e 100%);
            border-radius: 14px;
            z-index: -1;
            box-shadow: 0 15px 40px rgba(26, 37, 47, 0.8);
        }

        .box-title {
            color: #2c3e50;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .box-content {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(26, 37, 47, 0.1);
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);
        }

        .viewer-area {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #6c757d;
            background: rgba(248, 249, 250, 0.9);
            border-radius: 6px;
            border: 2px dashed #dee2e6;
            transition: all 0.3s ease;
        }

        .viewer-area:hover {
            background: rgba(248, 249, 250, 1);
            border-color: #adb5bd;
        }

        .mini-section {
            margin-bottom: 1rem;
            text-align: center;
            padding: 0.5rem;
        }

        .mini-section h6 {
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
        }

        .box-buttons {
            display: flex;
            gap: 0.6rem;
            justify-content: center;
            align-items: center;
            padding: 0.8rem 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            margin-top: auto;
            border-top: 2px solid rgba(26, 37, 47, 0.2);
        }

        .box-buttons .btn-custom {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            min-width: 90px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .box-buttons .btn-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        /* Responsividade para mobile */
        @media (max-width: 768px) {
            .main-boxes-row {
                flex-direction: column;
            }
            
            .main-box {
                margin-bottom: 2.5rem;
                min-height: 320px;
            }
            
            .box-buttons {
                flex-direction: row;
                gap: 0.4rem;
                padding: 0.6rem 0.3rem;
            }
            
            .box-buttons .btn-custom {
                font-size: 0.8rem;
                padding: 0.4rem 0.6rem;
                min-width: 70px;
            }
            
            .main-container {
                margin: 1rem;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    MDLIB 2.0
                </a>
                
                <div class="ms-auto">
                    @guest
                        <a href="{{ route('login') }}" class="user-icon">
                            <i class="fas fa-user"></i>
                        </a>
                    @else
                        <div class="dropdown">
                            <a class="user-icon" href="#" role="button" data-bs-toggle="dropdown">
                                U
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('meus-dados') }}">Meus Dados</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="alert('Logout')">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
