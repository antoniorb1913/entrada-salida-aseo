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
            @if(auth()->user()->rol !== 'admin')
                <a href="{{ route('logout') }}" 
                    class="btn btn-outline-danger btn-sm d-flex align-items-center"
                    onclick="return confirm('¿Estás seguro de que quieres cerrar la sesión?');">
                    <i class="bi bi-box-arrow-right me-2"></i> Salir
                </a>
            @else
                <a href="{{ auth()->user()->rol === 'admin' ? route('admin') : route('acceso') }}" class="btn btn-outline-secondary btn-sm">
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
            @if(session('debug_sso'))
    <div style="background: #1a202c; color: #fff; padding: 20px; font-family: monospace; border-radius: 8px; margin: 20px; border: 2px solid #dd6b20; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="color: #dd6b20; margin-top: 0; font-size: 1.25rem; margin-bottom: 15px;">🔍 DEBUG: Información de Roles Recibida</h3>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
            <tr style="border-bottom: 1px solid #2d3748;">
                <td style="padding: 8px 5px; font-weight: bold; width: 180px; color: #cbd5e0;">Email del Usuario:</td>
                <td style="padding: 8px 5px; color: #e2e8f0;">{{ session('debug_sso')['email'] }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #2d3748;">
                <td style="padding: 8px 5px; font-weight: bold; color: #cbd5e0;">Rol Global recibido:</td>
                <td style="padding: 8px 5px; color: #63b3ed;">"{{ session('debug_sso')['rol_global'] }}"</td>
            </tr>
            <tr style="border-bottom: 1px solid #2d3748;">
                <td style="padding: 8px 5px; font-weight: bold; color: #cbd5e0;">Rol Módulo recibido:</td>
                <td style="padding: 8px 5px; color: #63b3ed;">"{{ session('debug_sso')['rol_modulo'] }}"</td>
            </tr>
            <tr style="border-bottom: 1px solid #2d3748;">
                <td style="padding: 8px 5px; font-weight: bold; color: #cbd5e0;">Rol tras filtros:</td>
                <td style="padding: 8px 5px; color: #4fd1c5;">"{{ session('debug_sso')['rol_recibido'] }}" <span style="font-size: 0.85em; color: #718096;">(Tratamiento de Alumno/Superadmin)</span></td>
            </tr>
            <tr>
                <td style="padding: 8px 5px; font-weight: bold; color: #cbd5e0;">Rol final guardado/leído:</td>
                <td style="padding: 8px 5px; color: #48bb78; font-size: 1.1em; font-weight: bold;">"{{ session('debug_sso')['rol_final'] }}"</td>
            </tr>
        </table>
        <p style="margin-bottom: 0; margin-top: 15px; font-size: 0.8em; color: #a0aec0; italic;">
            * Nota: Este panel informativo es temporal. Si recargas la página (F5) desaparecerá de la pantalla.
        </p>
    </div>
@endif

            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Paso 1: Selecciona la Etapa</h2>
                <p class="text-muted fs-5">¿De qué etapa es el alumno?</p>
                @if(auth()->user()->rol !== 'admin')
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
    @if(auth()->user()->rol !== 'admin')
        {{-- BANNER DE SENTIDO COMÚN (FOOTER) --}}
        @include('Footer.footer')
    @endif
</body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Espera a que la página web termine de cargarse por completo
        document.addEventListener("DOMContentLoaded", function() {
            
            // Buscamos si hay algún cartel de error rojo en la pantalla (.alert-danger)
            const alerta = document.querySelector('.alert-danger');
            
            // Si el cartel de error existe...
            if (alerta) {
                // Ponemos un temporizador para que se active justo a los 5 segundos (5000 milisegundos)
                setTimeout(() => {
                    
                    // Intentamos cerrar el cartel usando la herramienta oficial de Bootstrap 
                    // para que haga un efecto de desvanecido suave muy elegante.
                    if (typeof bootstrap !== 'undefined') {
                        const bsAlert = new bootstrap.Alert(alerta);
                        bsAlert.close(); // Cierra el cartel con animación
                    } else {
                        // Si por algún problema de red Bootstrap no se hubiera cargado, 
                        // borramos el cartel directamente del HTML "a la fuerza" para que no se quede bloqueado.
                        alerta.remove(); 
                    }
                    
                }, 5000); // 5000ms = 5 segundos de margen para que el profesor lo lea
            }
        });
    </script>
</body>
</html>