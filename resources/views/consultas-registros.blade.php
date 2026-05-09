<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultas - Selecciona Filtro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column; }
        .navbar-custom { background-color: #ffffff; border-bottom: 2px solid #dee2e6; }
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; }
        /* El estilo exacto de tus tarjetas amarillas */
        .card-step {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            text-decoration: none;
            padding: 15px 20px; /* Padding grande para que sean altas */
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: #ffc107; /* El amarillo de tu captura */
            color: #212529; /* Texto oscuro para contrastar con el amarillo */
        }
        .card-step:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
            color: #000;
        }
        .card-step i { font-size: 4.5rem; margin-bottom: 20px; }
        .card-step span { font-size: 1.5rem; font-weight: 800; text-transform: uppercase; }
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
                    <i class="bi bi-door-open me-2 text-primary"></i>Acceso al Aseo
                </a>
            </span>
    
            <a href="{{ route('admin') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    
        {{-- Sub-franja inferior: Nombre del usuario con icono verde "Online" --}}
        <div class="w-100 border-top mt-2 pt-1 pb-1 bg-light">
            <div class="container text-center">
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    {{-- Icono en verde success para simular estado activo --}}
                    <i class="bi bi-person-circle me-1 text-success"></i>
                    Sesión de: {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}
                </small>
            </div>
        </div>
    </nav>

    <main class="main-content mt-5 pt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Selecciona el Tipo de Consulta</h2>
                <p class="text-muted fs-5">Filtros para el historial de registros</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger text-center mx-auto mb-4" style="max-width: 600px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="row justify-content-center g-4">
                
                {{-- Filtro 1: FECHA --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('consulta.fecha') }}" class="card-step shadow">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>FECHA</span>
                    </a>
                </div>

                {{-- Filtro 2: GRUPO --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('consulta.grupo') }}" class="card-step shadow">
                        <i class="bi bi-people-fill"></i>
                        <span>GRUPO</span>
                    </a>
                </div>

                {{-- Filtro 3: PROFESOR --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('consulta.profesor') }}" class="card-step shadow">
                        <i class="bi bi-person-video3"></i>
                        <span>PROFESOR</span>
                    </a>
                </div>

                {{-- Filtro 4: ALUMNO --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('consulta.alumno') }}" class="card-step shadow">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>ALUMNO</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

</body>
</html>