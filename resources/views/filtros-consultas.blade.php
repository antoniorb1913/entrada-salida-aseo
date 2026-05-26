@php
    // Detectamos qué tipo de filtro queremos mostrar
    $tipo = $tipo ?? 'fecha'; // Por defecto fecha si no se especifica
    
    // Configuramos los detalles según el tipo
    $config = [
        'alumno'   => ['icon' => 'bi-person-badge',  'color' => 'color-alumno', 'color-fondo' => 'fondo-alumno', 'title' => 'Filtro por Alumno/a'],
        'profesor' => ['icon' => 'bi-person-video3', 'color' => 'color-profesor', 'color-fondo' => 'fondo-profesor', 'title' => 'Filtro por Profesor/a'],
        'grupo'    => ['icon' => 'bi-people-fill',   'color' => 'color-grupo', 'color-fondo' => 'fondo-grupo', 'title' => 'Filtro por Grupo'],
        'fecha'    => ['icon' => 'bi-calendar-range', 'color' => 'color-fecha', 'color-fondo' => 'fondo-fecha', 'title' => 'Filtro por Fecha'],
    ][$tipo];
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $config['title'] }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { background-color: rgb(244, 242, 238); min-height: 100vh; display: flex; flex-direction: column; font-family: 'Segoe UI', sans-serif;}
        @media (max-width: 576px) {
        body {
            padding-top: 10%;
        }
    }
        .navbar-custom { background-color: rgb(253, 252, 249); border-bottom: 2px solid #dee2e6; }
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; }
        .form-card { background-color: rgb(255, 252, 252); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .ts-wrapper.form-select-lg .ts-control { border-radius: 10px !important; padding: 12px 15px !important; font-size: 1.1rem !important; }
        .nav-color {
            background-color: rgb(246, 246, 244);
        }
        .color-fecha {
            color: #c0535a;
        }
        .color-grupo {
            color: rgb(77, 106, 142);
        }
        .color-profesor {
            color: #3d6851;
        }
        .color-alumno {
            color: rgb(165, 152, 99);
        }

        /* BOTÓN FECHA (Rojizo) */
        .fondo-fecha, .fondo-fecha:hover, .fondo-fecha:active, .fondo-fecha:focus {
            background-color: #c0535a !important;
            color: white !important;
            border: none !important;
        }

        /* BOTÓN GRUPO (Azulado) */
        .fondo-grupo, .fondo-grupo:hover, .fondo-grupo:active, .fondo-grupo:focus {
            background-color: rgb(77, 106, 142) !important;
            color: white !important;
            border: none !important;
        }

        /* BOTÓN PROFESOR (Verdoso) */
        .fondo-profesor, .fondo-profesor:hover, .fondo-profesor:active, .fondo-profesor:focus {
            background-color: #3d6851 !important;
            color: white !important;
            border: none !important;
        }

        /* BOTÓN ALUMNO (Dorado/Oliva) */
        .fondo-alumno, .fondo-alumno:hover, .fondo-alumno:active, .fondo-alumno:focus {
            background-color: rgb(165, 152, 99) !important;
            color: white !important;
            border: none !important;
        }
        /* EFECTO DE ELEVACIÓN (Para que se note que pulsas sin cambiar el color) */
        .fondo-fecha:hover, .fondo-grupo:hover, .fondo-profesor:hover, .fondo-alumno:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.2) !important;
            filter: none !important;
        }
        .fondo-fecha, .fondo-grupo, .fondo-profesor, .fondo-alumno {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        }
        /* Ajuste para el botón en el HTML */
        .btn-check:focus + .btn, .btn:focus {
            box-shadow: none !important;
        }
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
                    <i class="bi bi-door-open me-2 text-primary"></i>Control de Salidas
                </a>
            </span>
    
            <a href="{{ route('registros') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    
        {{-- Franja inferior: Sesión activa con icono verde --}}
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
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="form-card text-center">
                        <i class="bi {{ $config['icon'] }} {{ $config['color'] }} mb-3" style="font-size: 4rem;"></i>
                        <h3 class="fw-bold text-secondary mb-2">{{ $config['title'] }}</h3>
                        
                        <form action="{{ route('registros.resultados') }}" method="GET">
                            
                            {{-- CASO ALUMNO --}}
                            @if($tipo == 'alumno')
                                <div class="mb-4 text-start">
                                    <label class="small fw-bold text-muted mb-2 text-uppercase">Listado de Alumnos</label>
                                    <select id="buscador-select" name="alumno_id" class="form-select-lg" required>
                                        <option value=""></option>
                                        @foreach($alumnos as $alumno)
                                            <option value="{{ $alumno->id }}">{{ $alumno->apellidos }}, {{ $alumno->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            {{-- CASO PROFESOR --}}
                            @elseif($tipo == 'profesor')
                                <div class="mb-4 text-start">
                                    <label class="small fw-bold text-muted mb-2 text-uppercase">Listado de Profesores</label>
                                    <select id="buscador-select" name="profesor_id" class="form-select-lg" required>
                                        <option value=""></option>
                                        @foreach($profesores as $profesor)
                                            <option value="{{ $profesor->id }}">{{ $profesor->apellidos }}, {{ $profesor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            {{-- CASO GRUPO --}}
                            @elseif($tipo == 'grupo')
                                <div class="mb-4 text-start">
                                    <label class="small fw-bold text-muted mb-2 text-uppercase">Selecciona el Curso</label>
                                    <select name="curso_id" class="form-select form-select-lg" required>
                                        <option value="" selected disabled>Elige un curso...</option>
                                        @foreach($cursos as $curso)
                                            <option value="{{ $curso->id }}">{{ $curso->nivel }} {{ $curso->letra }} {{ $curso->modalidad }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            {{-- CASO FECHA --}}
                            @else
                                <div class="row text-start mb-4">
                                    <div class="col-6">
                                        <label class="form-label fw-bold small text-muted">DESDE:</label>
                                        <input type="date" name="fecha_inicio" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold small text-muted">HASTA:</label>
                                        <input type="date" name="fecha_fin" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            @endif
                            
                            <button type="submit" class="btn btn-lg rounded-pill fw-bold px-5 w-100 shadow-sm mt-2 {{ $config['color-fondo'] }} text-white">
                                <i class="bi bi-file-earmark-text me-2"></i>Ver Registros
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        // Espera a que la página cargue los elementos antes de aplicar los cambios visuales
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. Metemos en una lista los IDs o nombres de los desplegables que queremos tunear
            // En este caso, el buscador general y el desplegable donde se eligen los cursos
            const selectores = ['#buscador-select', 'select[name="curso_id"]'];

            // 2. Recorremos esa lista para aplicar el cambio uno por uno
            selectores.forEach(selector => {
                const el = document.querySelector(selector);
                
                // Si el desplegable existe en la pantalla actual...
                if (el) {
                    // Convertimos el desplegable nativo en un menú interactivo con esteroides
                    new TomSelect(el, {
                        create: false,           // Evita que el usuario se invente opciones o escriba texto libre
                        allowEmptyOption: true,  // Permite dejar la casilla vacía si Dirección quiere resetear los filtros
                        placeholder: "Selecciona una opción...", // El texto de ayuda que sale de fondo
                        maxOptions: 50,          // Límite de opciones visibles en el scroll a la vez para que la web no se sature
                        
                        // 3. Render: Nos permite personalizar el diseño visual (HTML/CSS) de las opciones
                        render: {
                            option: function(data, escape) {
                                // Le mete un pequeño margen arriba y abajo (py-2) para que las opciones no estén pegadas y sea fácil 
                                // pulsar en pantallas táctiles
                                return '<div class="py-2">' + escape(data.text) + '</div>';
                            }
                        }
                    });
                }
            });
        });
</script>
</body>
</html>