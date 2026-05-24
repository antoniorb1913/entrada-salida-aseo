<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seleccionar Etapa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: rgb(244, 242, 238); min-height: 100vh; display: flex; flex-direction: column;}
        @media (max-width: 576px) {
        body {
            padding-top: 10%;
        }
    }
        .navbar-custom { background-color: rgb(253, 252, 249); border-bottom: 2px solid #dee2e6; }
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
        .nav-color {
            background-color: rgb(246, 246, 244);
        }
        .etapas-color {
            background-color: #c0535a;
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
        .salida-color {
            background-color: rgb(88, 127, 175) !important;
            color: white !important;
            border: none;
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
                    <i class="bi bi-door-open me-2 text-primary"></i>Control de Salidas
                </a>
            </span>
            
            {{-- EL BOTÓN DINÁMICO --}}
            @if(auth()->user()->rol === 'profesor')
                <a href="{{ route('logout') }}" 
                    class="btn btn-outline-danger btn-sm d-flex align-items-center"
                    onclick="return confirm('¿Estás seguro de que quieres cerrar la sesión?');">
                    <i class="bi bi-box-arrow-right me-2"></i> Salir
                </a>
            @else
                <a href="{{ auth()->user()->rol === 'admin' ? route('admin') : route('consulta') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-2"></i> Volver
                </a>
            @endif
        </div>
    
        {{-- Franja del nombre: Estrecha y centrada --}}
        <div class="w-100 border-top mt-2 pt-1 pb-1 nav-color">
            <div class="container text-center">
                <small class="text-uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    <i class="bi bi-person-circle me-1 text-success"></i>
                    
                    <span class="text-secondary">Sesión de:</span>
                    
                    <span class="text-success">
                        {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}
                    </span>
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
                @if(auth()->user()->rol === 'profesor')
                    <div class="mt-3 text-center">
                        <span class="badge salida-color fs-6 p-2 px-3 rounded-pill">
                            <i class="bi bi-people-fill me-2"></i>Alumnos fuera: <strong>{{ $aforo->total }}</strong>
                        </span>
                    </div>
                @endif
            </div>

            <div class="row justify-content-center g-4">
                {{-- Recorremos las etapas que vienen del controlador --}}
                @foreach($etapas as $etapa)
                    <div class="col-12 col-md-4 col-lg-3">
                        <a href="{{ route('acceso.modalidades', $etapa) }}" class="card-step etapas-color text-white shadow text-decoration-none">
                            <i class="bi bi-mortarboard"></i>
                            <span class="text-uppercase fw-bold">{{ $etapa }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
    {{-- BANNER DE SENTIDO COMÚN (FOOTER) --}}
    <footer class="mt-5 py-4 text-center w-100">
        <div class="container">
            <div class="d-inline-flex align-items-center bg-white px-4 py-2 rounded-pill shadow-sm border border-info-subtle">
                <i class="bi bi-info-circle-fill text-info fs-5 me-2"></i>
                <span class="text-muted" style="font-size: 0.95rem;">
                    <strong>Recordatorio:</strong> El sistema es una herramienta de apoyo. Ante urgencias, siempre <strong>prevalece el sentido común</strong>.
                </span>
            </div>
        </div>
    </footer>
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