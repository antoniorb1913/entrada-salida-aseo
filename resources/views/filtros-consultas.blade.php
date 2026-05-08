@php
    // Detectamos qué tipo de filtro queremos mostrar
    $tipo = $tipo ?? 'fecha'; // Por defecto fecha si no se especifica
    
    // Configuramos los detalles según el tipo
    $config = [
        'alumno'   => ['icon' => 'bi-person-badge',  'color' => 'text-warning', 'title' => 'Filtro por Alumno/a'],
        'profesor' => ['icon' => 'bi-person-video3', 'color' => 'text-success', 'title' => 'Filtro por Profesor/a'],
        'grupo'    => ['icon' => 'bi-people-fill',   'color' => 'text-primary', 'title' => 'Filtro por Grupo'],
        'fecha'    => ['icon' => 'bi-calendar-range', 'color' => 'text-danger',  'title' => 'Filtro por Fecha'],
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
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column; font-family: 'Segoe UI', sans-serif; }
        @media (max-width: 576px) {
            body {
                padding-top: 20%;
            }
        }
        .navbar-custom { background-color: #fff; border-bottom: 2px solid #dee2e6; }
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; }
        .form-card { background-color: #fff; border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .ts-wrapper.form-select-lg .ts-control { border-radius: 10px !important; padding: 12px 15px !important; font-size: 1.1rem !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom py-3 shadow-sm fixed-top">
        <div class="container">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold">
                <i class="bi bi-search me-2 {{ $config['color'] }}"></i>Consultas
            </span>
            <a href="{{ route('registros') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </nav>

    <main class="main-content">
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
                                    <select id="buscador-select" name="user_id" class="form-select-lg" required>
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
                                            <option value="{{ $curso->id }}">{{ $curso->etapas }} {{ $curso->nivel }} {{ $curso->letra }}</option>
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
                            
                            <button type="submit" class="btn btn-lg rounded-pill fw-bold px-5 w-100 shadow-sm mt-2 {{ str_replace('text', 'btn', $config['color']) }} text-white">
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
        document.addEventListener("DOMContentLoaded", function() {
            const el = document.getElementById('buscador-select');
            if (el) {
                new TomSelect(el, {
                    create: false,
                    allowEmptyOption: true,
                    placeholder: "Escribe para buscar...",
                });
            }
        });
    </script>
</body>
</html>