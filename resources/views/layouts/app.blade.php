<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MDLIB 2.0') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #a8d8ea 0%, #7fcdcd 100%);
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #5a6c7d 0%, #4a5c6d 100%);
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
        }

        .nav-tabs-custom {
            background: linear-gradient(135deg, #7fcdcd 0%, #6bb6b6 100%);
            border-radius: 10px 10px 0 0;
            padding: 0.5rem 1rem;
            margin-bottom: 0;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            background: transparent;
            color: #2c3e50;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            background: rgba(255,255,255,0.2);
            color: #1a252f;
        }

        .nav-tabs-custom .nav-link.active {
            background: rgba(255,255,255,0.3);
            color: #1a252f;
            font-weight: bold;
        }

        .main-container {
            background: linear-gradient(135deg, #5a6c7d 0%, #4a5c6d 100%);
            margin: 4rem 2rem 2rem 2rem;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            min-height: 70vh;
        }

        .title-section {
            text-align: center;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .title-section h1 {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin: 0;
            line-height: 1.3;
        }

        .content-area {
            background: linear-gradient(135deg, #a8d8ea 0%, #7fcdcd 100%);
            border-radius: 10px;
            padding: 2rem;
            min-height: 400px;
        }

        /* Main Boxes Row */
        .main-boxes-row {
            margin-bottom: 2rem;
        }

        /* Main Box Styling */
        .main-box {
            background: linear-gradient(135deg, #b8e6e6 0%, #a8d6d6 100%);
            border-radius: 10px;
            border: 3px solid #5a6c7d;
            padding: 1rem;
            margin-bottom: 1rem;
            height: 450px; /* Altura aumentada para mais espaço */
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 8px 25px rgba(90, 108, 125, 0.3);
        }

        .box-title {
            color: #2c3e50;
            font-weight: bold;
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 0.5rem;
            line-height: 1.2;
            flex-shrink: 0;
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
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Mini sections para o terceiro box */
        .mini-section {
            background: rgba(255,255,255,0.5);
            border-radius: 5px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .mini-section h6 {
            color: #2c3e50;
            font-weight: bold;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .mini-section:last-child {
            margin-bottom: 0;
        }

        .pdf-container, .glosa-container {
            background: linear-gradient(135deg, #b8e6e6 0%, #a8d6d6 100%);
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 1rem;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border: 3px solid #5a6c7d;
            position: relative;
        }

        .pdf-container h3, .glosa-container h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .expand-icon {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            color: #5a6c7d;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .side-panel {
            background: linear-gradient(135deg, #b8e6e6 0%, #a8d6d6 100%);
            border-radius: 10px;
            padding: 1.5rem;
            border: 3px solid #5a6c7d;
            margin-bottom: 1rem;
        }

        .side-panel-unified {
            background: linear-gradient(135deg, #b8e6e6 0%, #a8d6d6 100%);
            border-radius: 10px;
            padding: 1rem;
            border: 3px solid #5a6c7d;
            margin-bottom: 1rem;
            min-height: 120px;
        }

        .side-panel h5, .side-panel-unified h5 {
            color: #2c3e50;
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
            background: linear-gradient(135deg, #5a6c7d 0%, #4a5c6d 100%);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #4a5c6d 0%, #3a4c5d 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            color: white;
        }

        .file-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }

        .file-table th {
            background: linear-gradient(135deg, #5a6c7d 0%, #4a5c6d 100%);
            color: white;
            font-weight: 500;
            padding: 1rem 0.5rem;
            text-align: center;
        }

        .file-table td {
            padding: 0.75rem 0.5rem;
            text-align: center;
            vertical-align: middle;
        }

        .file-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .user-icon {
            background: linear-gradient(135deg, #5a6c7d 0%, #4a5c6d 100%);
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
