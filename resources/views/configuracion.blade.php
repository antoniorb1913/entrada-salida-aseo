@php
    // 1. Cargamos el "pack" de configuración centralizado
    $config = \App\Models\Configuracion::todas();
    
    // Asignamos las variables que ya usa el resto de tu HTML
    $maxSalidas = $config->max_salidas;
    $tiempoEspera = $config->tiempo_espera; 
    $tCancelacion = $config->tiempo_cancelacion;
    
    // Asumimos que guardas el estado en las variables de tu configuración (true = disponible, false = averiado)
    $aseoHombresDisponible = $config->aseo_hombres_disponible ?? true;
    $aseoMujeresDisponible = $config->aseo_mujeres_disponible ?? true;
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuración del Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: rgb(244, 242, 238); min-height: 100vh; display: flex; flex-direction: column; padding-top: 2%;}
        @media (max-width: 576px) {
            body {
                padding-top: 10%;
            }
        }
        .config-card { background-color: rgb(255, 252, 252); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 30px; margin-bottom: 30px; }
        .form-label { font-weight: bold; color: #495057; }
        .navbar-custom { background-color: rgb(253, 252, 249); border-bottom: 2px solid #dee2e6; }
        .student-list { max-height: 350px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0 0 10px 10px; padding: 10px; background: #fafafa; border-top: none;}
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; }
        .nav-color {
            background-color: rgb(246, 246, 244);
        }
        .guardar-color {
            background-color: #2b5471;
        }
        .activo {
            color: #278943;
        }
        .nav-tabs-custom .nav-link {
            border: 1px solid #dee2e6;
            color: #6c757d;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .nav-tabs-custom .nav-link.active {
            background-color: #fafafa !important;
            border-bottom-color: #fafafa !important;
            color: #212529;
        }
        /* Ajuste estético para el banner de éxito */
        .alerta-flotante {
            margin-top: 5rem !important;
            border-radius: 10px;
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
    
            <a href="{{ route('admin') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    
        {{-- Franja inferior estrecha con el icono en VERDE activo --}}
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
    
    @if(session('success'))
        <div class="container">
            <div id="bannerSuccess" class="alert alert-success alert-dismissible fade show shadow-sm alerta-flotante mx-auto" role="alert" style="max-width: 600px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <main class="main-content mt-5 pt-5">
        <div class="container">
            <form action="{{ route('configuracion.guardar') }}" method="POST">
                @csrf
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-secondary">Panel de configuración</h2>
                    <p class="text-muted">Parámetros para la entrada y salida al aseo</p>
                </div>
                <div class="row">
                    {{-- AJUSTES GLOBALES --}}
                    <div class="col-lg-5">
                        <div class="config-card">
                            <h4 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-sliders text-info me-2"></i>Límites</h4>
                            
                            {{-- LÍMITE DE SALIDAS DIARIAS --}}
                            <div class="mb-4">
                                <label class="form-label">Límite de salidas diarias</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-door-open"></i></span>
                                    <input type="number" name="max_salidas" class="form-control" value="{{ $maxSalidas }}" min="1" required>
                                    <span class="input-group-text">veces</span>
                                </div>
                            </div>

                            {{-- TIEMPO DE ESPERA --}}
                            <div class="mb-4">
                                <label class="form-label">Tiempo de espera (Penalización)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-stopwatch"></i></span>
                                    <input type="number" name="tiempo_espera" class="form-control" value="{{ $tiempoEsperaMinutos }}" min="0" required>
                                    <span class="input-group-text">minutos</span>
                                </div>
                            </div>

                            {{-- TIEMPO DE ARREPENTIMIENTO --}}
                            <div class="mb-4">
                                <label class="form-label">Tiempo de arrepentimiento (Cancelar)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-arrow-counterclockwise"></i></span>
                                    <input type="number" name="tiempo_cancelacion" class="form-control" value="{{ $tiempoCancelacion }}" min="0" max="30" required>
                                    <span class="input-group-text">segundos</span>
                                </div>
                                <div class="form-text">Segundos para poder anular la salida antes de que se registre.</div>
                            </div>

                            {{-- NUEVO: ESTADO DE LOS ASEOS (DISPONIBILIDAD / AVERÍAS) --}}
                            <div class="mb-2 border-top pt-3">
                                <label class="form-label mb-2"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Estado Operativo de Aseos</label>
                                
                                <div class="form-check form-switch mb-2">
                                    <input type="hidden" name="aseo_hombres_disponible" value="0">
                                    <input class="form-check-input" type="checkbox" name="aseo_hombres_disponible" value="1" id="switchHombres" {{ $aseoHombresDisponible ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="switchHombres">
                                        Aseo de Hombres Disponible
                                    </label>
                                </div>

                                <div class="form-check form-switch">
                                    <input type="hidden" name="aseo_mujeres_disponible" value="0">
                                    <input class="form-check-input" type="checkbox" name="aseo_mujeres_disponible" value="1" id="switchMujeres" {{ $aseoMujeresDisponible ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="switchMujeres">
                                        Aseo de Mujeres Disponible
                                    </label>
                                </div>
                                <div class="form-text mt-2">Si desmarcas un aseo, los profesores no podrán autorizar la salida de los alumnos de dicho género.</div>
                            </div>
                        </div>
                    </div>

                    {{-- BUSCADOR Y PESTAÑAS DE ALUMNO --}}
                    <div class="col-lg-7">
                        <div class="config-card">
                            <h4 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-people-fill text-primary me-2"></i>Permisos Especiales de Alumnos</h4>
                            
                            {{-- EL BUSCADOR GLOBAL --}}
                            <div class="input-group mb-4 shadow-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="buscadorAlumnos" class="form-control border-start-0 ps-0" placeholder="Buscar por nombre, apellidos o curso en cualquier pestaña...">
                            </div>

                            {{-- ESTRUCTURA DE PESTAÑAS (TABS) --}}
                            <ul class="nav nav-tabs nav-tabs-custom" id="tabAlumnos" role="tablist">
                                <li class="nav-item flex-fill text-center" role="presentation">
                                    <button class="nav-link w-100 active rounded-top-4" id="medicas-tab" data-bs-toggle="tab" data-bs-target="#medicas-pane" type="button" role="tab"><i class="bi bi-person-heart text-danger me-2"></i>E. Médicas</button>
                                </li>
                                <li class="nav-item flex-fill text-center" role="presentation">
                                    <button class="nav-link w-100 rounded-top-4" id="tutor-tab" data-bs-toggle="tab" data-bs-target="#tutor-pane" type="button" role="tab"><i class="bi bi-person-plus-fill text-warning me-2"></i>Acompañante</button>
                                </li>
                            </ul>

                            {{-- CONTENIDO DE LAS PESTAÑAS --}}
                            <div class="tab-content" id="tabAlumnosContent">
                                
                                {{-- PESTAÑA 1: EXCEPCIONES MÉDICAS --}}
                                <div class="tab-pane fade show active" id="medicas-pane" role="tabpanel" aria-labelledby="medicas-tab">
                                    <div class="student-list">
                                        @foreach($alumnos as $alumno)
                                            @php
                                                $etapa = $alumno->curso->etapas->value ?? $alumno->curso->etapas ?? '';
                                                $modalidad = $alumno->curso->modalidad ?? '';
                                                $nivel = $alumno->curso->nivel ?? '';
                                                $letra = $alumno->curso->letra ?? '';
                                                $searchString = strtolower($alumno->apellidos . ' ' . $alumno->nombre . ' ' . $nivel . ' ' . $letra . ' ' . $modalidad);
                                            @endphp
                                            <div class="form-check form-switch mb-2 p-2 border-bottom student-item" data-search="{{ $searchString }}">
                                                <input class="form-check-input ms-0 me-3" type="checkbox" name="excepciones[]" value="{{ $alumno->id }}" id="medica_{{ $alumno->id }}" {{ $alumno->excepcion_limite ? 'checked' : '' }} style="transform: scale(1.3);">
                                                <label class="form-check-label d-flex align-items-center justify-content-between w-100 py-1" for="medica_{{ $alumno->id }}">
                                                    <span class="fw-bold me-2 flex-grow-1 text-wrap" style="line-height: 1.2;">
                                                        {{ $alumno->apellidos }}, {{ $alumno->nombre }}
                                                    </span>
                                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-pill px-3 py-1 fw-bold flex-shrink-0 ms-auto">
                                                        <i class="bi bi-mortarboard-fill me-1"></i> {{ $nivel }} {{ $letra }} {{ $modalidad }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- PESTAÑA 2: REQUIERE ACOMPAÑANTE / TUTOR --}}
                                <div class="tab-pane fade" id="tutor-pane" role="tabpanel" aria-labelledby="tutor-tab">
                                    <div class="student-list">
                                        @foreach($alumnos as $alumno)
                                            @php
                                                $etapa = $alumno->curso->etapas->value ?? $alumno->curso->etapas ?? '';
                                                $modalidad = $alumno->curso->modalidad ?? '';
                                                $nivel = $alumno->curso->nivel ?? '';
                                                $letra = $alumno->curso->letra ?? '';
                                                $searchString = strtolower($alumno->apellidos . ' ' . $alumno->nombre . ' ' . $nivel . ' ' . $letra . ' ' . $modalidad);
                                            @endphp
                                            <div class="form-check form-switch mb-2 p-2 border-bottom student-item" data-search="{{ $searchString }}">
                                                <input class="form-check-input ms-0 me-3" type="checkbox" name="necesita_tutor[]" value="{{ $alumno->id }}" id="tutor_{{ $alumno->id }}" {{ $alumno->necesita_tutor ? 'checked' : '' }} style="transform: scale(1.3);">
                                                <label class="form-check-label d-flex align-items-center justify-content-between w-100 py-1" for="tutor_{{ $alumno->id }}">
                                                    <span class="fw-bold me-2 flex-grow-1 text-wrap" style="line-height: 1.2;">
                                                        {{ $alumno->apellidos }}, {{ $alumno->nombre }}
                                                    </span>
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1 fw-bold flex-shrink-0 ms-auto">
                                                        <i class="bi bi-mortarboard-fill me-1"></i> {{ $nivel }} {{ $letra }} {{ $modalidad }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-2">
                    <button type="submit" class="btn guardar-color btn-lg px-3 text-white shadow-sm fw-bold">
                        <i class="bi bi-floppy-fill me-2"></i>Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const banner = document.getElementById('bannerSuccess');
            if (banner) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(banner);
                    bsAlert.close();
                }, 3000);
            }
        });

        document.getElementById('buscadorAlumnos').addEventListener('keyup', function() {
            const normalizarTexto = (texto) => {
                return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            };
    
            let filtro = normalizarTexto(this.value);
            let alumnos = document.querySelectorAll('.student-item');
    
            alumnos.forEach(function(alumno) {
                let textoAlumno = normalizarTexto(alumno.getAttribute('data-search'));
                
                if (textoAlumno.includes(filtro)) {
                    alumno.style.display = 'block';
                } else {
                    alumno.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>