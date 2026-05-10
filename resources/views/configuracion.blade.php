<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuración del Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: rgb(244, 242, 238); min-height: 100vh; display: flex; flex-direction: column; }
        @media (max-width: 576px) {
        body {
            padding-top: 10%;
        }
    }
        .config-card { background-color: rgb(255, 252, 252); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 30px; margin-bottom: 30px; }
        .form-label { font-weight: bold; color: #495057; }
        .navbar-custom { background-color: rgb(253, 252, 249); border-bottom: 2px solid #dee2e6; }
        .student-list { max-height: 350px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 10px; padding: 10px; background: #fafafa;}
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; }
        .nav-color {
            background-color: rgb(246, 246, 244);
        }
        .guardar-color {
            background-color: #2b5471;
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
                    <i class="bi bi-door-open me-2 text-primary"></i>Acceso al Aseo
                </a>
            </span>
    
            <a href="{{ route('admin') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    
        {{-- Franja inferior estrecha con el icono en VERDE activo --}}
        <div class="w-100 border-top mt-2 pt-1 pb-1 nav-color">
            <div class="container text-center">
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    {{-- Icono en verde success para indicar sesión activa --}}
                    <i class="bi bi-person-circle me-1 text-success"></i>
                    Sesión de: {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}
                </small>
            </div>
        </div>
    </nav>
    
    
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mt-5 pt-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    <main class="main-content mt-5 pt-5">
        <div class="container">
            <form action="{{ route('configuracion.guardar') }}" method="POST">
                @csrf
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-secondary">Panel de configuración</h2>
                    <p class="text-muted">Parametros para la entrada y salida al aseo</p>
                </div>
                <div class="row">
                    {{-- AJUSTES GLOBALES --}}
                    <div class="col-lg-5">
                        <div class="config-card">
                            <h4 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-sliders text-info me-2"></i>Límites</h4>
                            
                            <div class="mb-4">
                                <label class="form-label">Límite de salidas diarias</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-door-open"></i></span>
                                    <input type="number" name="max_salidas" class="form-control" value="{{ $maxSalidas }}" min="1" required>
                                    <span class="input-group-text">veces</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Tiempo de espera (Penalización)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-stopwatch"></i></span>
                                    <input type="number" name="tiempo_espera" class="form-control" value="{{ $tiempoEsperaMinutos }}" min="0" required>
                                    <span class="input-group-text">minutos</span>
                                </div>
                            </div>

                            {{-- EL NUEVO PARÁMETRO DE CANCELACIÓN (Añadido) --}}
                            <div class="mb-4">
                                <label class="form-label">Tiempo de arrepentimiento (Cancelar)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-arrow-counterclockwise"></i></span>
                                    <input type="number" name="tiempo_cancelacion" class="form-control" value="{{ $tiempoCancelacion }}" min="0" max="30" required>
                                    <span class="input-group-text">segundos</span>
                                </div>
                                <div class="form-text">Segundos para poder anular la salida antes de que se registre.</div>
                            </div>
                        </div>
                    </div>

                    {{-- BUSCADOR Y EXCEPCIONES --}}
                    <div class="col-lg-7">
                        <div class="config-card">
                            <h4 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-person-heart text-danger me-2"></i>Excepciones Médicas</h4>
                            
                            {{-- EL BUSCADOR --}}
                            <div class="input-group mb-3 shadow-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="buscadorAlumnos" class="form-control border-start-0 ps-0" placeholder="Buscar por nombre, apellidos o curso...">
                            </div>

                            <div class="student-list" id="listaAlumnos">
                                @foreach($alumnos as $alumno)
                                    @php
                                        // Extraemos el valor seguro para evitar el error del Enum
                                        $etapa = $alumno->curso->etapas->value ?? $alumno->curso->etapas ?? '';
                                        $modalidad = $alumno->curso->modalidad ?? '';
                                        $nivel = $alumno->curso->nivel ?? '';
                                        $letra = $alumno->curso->letra ?? '';
                                        
                                        // Creamos un string de busqueda limpio
                                        $searchString = strtolower($alumno->apellidos . ' ' . $alumno->nombre . ' ' . $nivel . ' ' . $letra . ' ' . $modalidad);
                                    @endphp

                                    <div class="form-check form-switch mb-2 p-2 border-bottom student-item" data-search="{{ $searchString }}">
                                        <input class="form-check-input ms-0 me-3" type="checkbox" name="excepciones[]" value="{{ $alumno->id }}" id="alumno_{{ $alumno->id }}" {{ $alumno->excepcion_limite ? 'checked' : '' }} style="transform: scale(1.3);">
                                        <label class="form-check-label d-flex align-items-center justify-content-between w-100 py-1" for="alumno_{{ $alumno->id }}">
                                            {{-- Nombre del alumno (Sin truncate y con flex-grow) --}}
                                            <span class="fw-bold me-2 flex-grow-1 text-wrap" style="line-height: 1.2;">
                                                {{ $alumno->apellidos }}, {{ $alumno->nombre }}
                                            </span>
                                            {{-- Badge del curso (Fijo y alineado a la derecha) --}}
                                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-pill px-3 py-1 fw-bold flex-shrink-0 ms-auto">
                                                <i class="bi bi-mortarboard-fill me-1"></i> {{ $nivel }} {{ $letra }} {{ $modalidad }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
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
        document.getElementById('buscadorAlumnos').addEventListener('keyup', function() {
            // 1. Función para quitar tildes y diacríticos de un texto
            const normalizarTexto = (texto) => {
                return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            };
    
            // 2. Normalizamos lo que el usuario ha escrito
            let filtro = normalizarTexto(this.value);
            let alumnos = document.querySelectorAll('.student-item');
    
            alumnos.forEach(function(alumno) {
                // 3. Normalizamos el texto de búsqueda del alumno (que ya viene del data-search)
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