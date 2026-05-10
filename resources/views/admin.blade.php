<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: rgb(244, 242, 238);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 576px) {
        body {
            padding-top: 10%;
        }
    }
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; }
        .navbar-custom {
            background-color: rgb(253, 252, 249);
            border-bottom: 2px solid #dee2e6;
        }
        .card-step {
            transition: all 0.3s ease;
            background-color: rgb(78, 94, 113);
            border: none;
            border-radius: 20px;
            text-decoration: none;
            padding: 20px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .fondo {
            background-color: #96b7d3;
        }
        .nav-color {
            background-color: rgb(246, 246, 244);
        }
        .card-step:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        .card-step i { font-size: 3.5rem; margin-bottom: 15px; }
        .card-step span { font-size: 1.5rem; font-weight: 700; }
        .activo {
            color: #278943;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom py-2 shadow-sm fixed-top">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold">
                @php
                    $urlInicio = (auth()->user()->rol === 'admin') ? route('admin') : route('acceso');
                @endphp
                <a href="{{ $urlInicio }}" class="text-decoration-none text-dark fw-bold">
                    <i class="bi bi-door-open me-1 text-primary"></i>Acceso al Aseo
                </a>
            </span>
    
            <a href="{{ route('logout') }}" 
               class="btn btn-outline-danger btn-sm py-1"
               onclick="return confirm('¿Estás seguro de que quieres cerrar la sesión?');">
                <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
            </a>
        </div>
    
        <div class="w-100 border-top mt-2 pt-1 pb-1 nav-color">
            <div class="container text-center">
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.80rem;">
                    <i class="bi bi-person-circle me-1 text-success"></i>
                    Sesión de: <strong class="activo">{{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</strong>
                </small>
            </div>
        </div>
    </nav>

    <main class="main-content mt-5 pt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Panel de Control General</h2>
                <p class="text-muted fs-5">Gestión de recursos y controles del sistema</p>
            </div>
    
            {{-- ... el resto de tus tarjetas (Acceso al Baño, Configuración, etc.) --}}

            <div class="row justify-content-center g-4">
                
                <div class="col-12 col-md-4 col-lg-3">
                    <a href="{{ route('acceso') }}" class="card-step text-white shadow">
                        <i class="bi bi-door-open"></i>
                        <span>Acceso al Baño</span>
                    </a>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <a href="{{ route('configuracion.index') }}" class="card-step bg-secondary text-white shadow">
                        <i class="bi bi-gear-fill"></i>
                        <span>Configuración</span>
                    </a>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <a href="{{ route('registros') }}" class="card-step fondo text-white shadow">
                        <i class="bi bi-journal-text"></i>
                        <span>Consultas</span>
                    </a>
                </div>

            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>