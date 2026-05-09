<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seleccionar Etapa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column;}
        .navbar-custom { background-color: #ffffff; border-bottom: 2px solid #dee2e6; }
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; margin-top: 5%; }
        .card-step {
            transition: all 0.3s ease;
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
        .card-step:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        .card-step i { font-size: 3.5rem; margin-bottom: 15px; }
        .card-step span { font-size: 1.5rem; font-weight: 700; }
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
            
            {{-- EL BOTÓN DINÁMICO --}}
            @if(auth()->user()->rol === 'profesor')
                <a href="{{ route('logout') }}" 
                   class="btn btn-outline-danger btn-sm d-flex align-items-center"
                   onclick="return confirm('¿Estás seguro de que quieres cerrar la sesión?');">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </a>
            @else
                <a href="{{ auth()->user()->rol === 'admin' ? route('admin') : route('consulta') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-2"></i> Volver
                </a>
            @endif
        </div>
    
        {{-- Franja del nombre: Estrecha y centrada --}}
        <div class="w-100 border-top mt-2 pt-1 pb-1 bg-light">
            <div class="container text-center">
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    <i class="bi bi-person-circle me-1 text-success"></i>
                    Sesión de: {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}
                </small>
            </div>
        </div>
    </nav>

    <main class="main-content mt-5 pt-5">
        <div class="container">
            
            {{-- Mensaje de Error (Si intentan entrar en Consultas sin permiso, aquí les sale el aviso) --}}
            @if(session('error'))
                <div class="alert alert-danger text-center mb-4 shadow-sm">
                    <i class="bi bi-shield-lock-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Paso 1: Selecciona la Etapa</h2>
                <p class="text-muted fs-5">¿De qué etapa es el alumno?</p>
            </div>

            <div class="row justify-content-center g-4">
                {{-- Recorremos las etapas que vienen del controlador --}}
                @foreach($etapas as $etapa)
                    <div class="col-12 col-md-4 col-lg-3">
                        <a href="{{ route('acceso.modalidades', $etapa) }}" class="card-step bg-primary text-white shadow text-decoration-none">
                            <i class="bi bi-mortarboard"></i>
                            <span class="text-uppercase fw-bold">{{ $etapa }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alerta = document.querySelector('.alert-danger');
            if (alerta) {
                setTimeout(() => {
                    // Verificamos que bootstrap esté disponible para evitar errores
                    if (typeof bootstrap !== 'undefined') {
                        const bsAlert = new bootstrap.Alert(alerta);
                        bsAlert.close();
                    } else {
                        // Si por lo que sea bootstrap no carga, la borramos a mano
                        alerta.remove();
                    }
                }, 5000);
            }
        });
    </script>
</body>
</html>
</html>