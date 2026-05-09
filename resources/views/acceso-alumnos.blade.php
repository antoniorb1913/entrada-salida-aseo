@php
    // 1. Cargamos el "pack" de configuración centralizado
    $config = \App\Models\Configuracion::todas();
    
    // Asignamos las variables que ya usa el resto de tu HTML
    $maxSalidas = $config->max_salidas;
    $tiempoEspera = $config->tiempo_espera; 
    $tCancelacion = $config->tiempo_cancelacion;
    
    $segundosFaltantes = 0;
    $ultimaSalida = null;
    
    // Buscamos si hay alguien fuera para el cronómetro global del curso
    foreach($alumnos as $a) {
        $regActivo = $a->registros->where('estado', \App\Enums\Estado::FUERA)->first();
        if ($regActivo) {
            if (!$ultimaSalida || $regActivo->fecha_salida > $ultimaSalida->fecha_salida) {
                $ultimaSalida = $regActivo;
            }
        }
    }

    if ($ultimaSalida) {
        $segundosDesdeSalida = intval(\Carbon\Carbon::parse($ultimaSalida->fecha_salida)->diffInSeconds(now()));
        if ($segundosDesdeSalida < $tiempoEspera) {
            $segundosFaltantes = $tiempoEspera - $segundosDesdeSalida;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Alumnos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; padding-top: 10%; margin-top: 8%;}

        .navbar-custom { background-color: #ffffff; border-bottom: 2px solid #dee2e6; }
        .student-card { 
            transition: all 0.2s; 
            border-radius: 15px; 
            border: 2px solid transparent; 
        }
        .student-card:hover { 
            background-color: #e9ecef; 
            transform: scale(1.02);
        }
        .on-break {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }
        .btn-confirmar-salida { min-width: 100px; } /* Mantiene el tamaño del botón estable */
    </style>
</head>
<body>
    <nav class="navbar navbar-custom bg-white py-2 shadow-sm fixed-top">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold">
                @php
                    $urlInicio = (auth()->user()->rol === 'admin') ? route('admin') : route('acceso');
                @endphp
                <a href="{{ $urlInicio }}" class="text-decoration-none text-dark fw-bold">
                    <i class="bi bi-door-open me-2 text-primary"></i>Acceso al Aseo
                </a>
            </span>
    
            <div class="d-flex gap-2">
                @php
                    // Mantenemos intacta tu lógica de redirección
                    if ($curso->letra === null) {
                        $urlVolver = route('acceso.niveles', [
                            'etapa' => $curso->etapas, 
                            'modalidad' => $curso->modalidad ?? 'comun'
                        ]);
                    } else {
                        $urlVolver = route('acceso.letras', [
                            'etapa' => $curso->etapas, 
                            'modalidad' => $curso->modalidad ?? 'comun', 
                            'nivel' => $curso->nivel
                        ]);
                    }
                @endphp
                
                <a href="{{ $urlVolver }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    
        {{-- Franja inferior estrecha para el nombre del usuario --}}
        <div class="w-100 border-top mt-2 pt-1 pb-1 bg-light">
            <div class="container text-center">
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    <i class="bi bi-person-circle me-1 text-success"></i>
                    Sesión de: {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}
                </small>
            </div>
        </div>
    </nav>

    <div class="container mb-5 mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-1">Selecciona al Alumno</h2>
            <span class="fw-bold text-muted fs-5">
                <i class="bi bi-layers me-2 text-primary"></i> 
                {{ $curso->nivel }} {{ $curso->letra ?? '' }} {{ $curso->modalidad }}
            </span>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($segundosFaltantes > 0)
            <div class="text-center mb-5">
                <span class="badge bg-primary text-white fs-4 p-3 shadow-sm rounded-pill">
                    <i class="bi bi-stopwatch me-1"></i> Siguiente salida en: 
                    <span id="timer-display">{{ gmdate('i:s', $segundosFaltantes) }}</span>
                </span>
            </div>
        @endif

        <div class="row g-3">
            @forelse($alumnos as $alumno)
                @php
                    $registroActivo = $alumno->registros->where('estado', \App\Enums\Estado::FUERA)->first();
                    $salidasHoy = $alumno->registros->filter(function($reg) {
                        return \Carbon\Carbon::parse($reg->fecha_salida)->isToday();
                    })->count();

                    // 2. LA LÓGICA MAESTRA
                    $limiteAlcanzado = !$alumno->excepcion_limite && ($salidasHoy >= $maxSalidas);
                @endphp
                
                <div class="col-md-6 col-lg-4">
                    <div class="card student-card shadow-sm p-3 position-relative {{ $registroActivo ? 'on-break' : '' }}">
                        

                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $alumno->apellidos }}, {{ $alumno->nombre }}</h6>
                                
                                @if($limiteAlcanzado)
                                    <div class="mt-1">
                                        <span class="text-danger bg-danger-subtle px-2 py-1 rounded" style="font-weight: bold; font-size: 0.8em;">Límite alcanzado ({{ $maxSalidas }})</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center text-muted mt-1" style="font-size: 0.9em;">
                                        <span>Salidas hoy: </span>
                                        <span class="ms-1 fw-bold salidas-numero" style="display: none;">
                                            {{ $salidasHoy }} {{ $alumno->excepcion_limite ? '(Sin límite)' : "/ $maxSalidas" }}
                                        </span>
                                        <span class="ms-1 fw-bold salidas-asteriscos">***</span>
                                        <button type="button" class="btn btn-link text-secondary p-0 ms-2 btn-toggle-ojito" title="Mostrar/Ocultar">
                                            <i class="bi bi-eye-slash-fill"></i>
                                        </button>
                                    </div>
                                @endif

                                @if($registroActivo)
                                    <div class="mt-1"><small class="text-danger fw-bold"><i class="bi bi-dot"></i> En el baño</small></div>
                                @endif
                            </div>

                            {{-- Botones de acción --}}
                            <div class="ms-2">
                                @if(!$registroActivo)
                                    {{-- 3. EL BOTÓN OBEDECE A LA NUEVA LÓGICA --}}
                                    @if(!$limiteAlcanzado)
                                        <form action="{{ route('registro.salida', $alumno->id) }}" method="POST" class="form-salida">
                                            @csrf
                                            @if($segundosFaltantes > 0)
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                    <i class="bi bi-hourglass-split"></i> Espera
                                                </button>
                                            @else
                                                {{-- BOTÓN DE CANCELACIÓN (Añadidos clase y data-tiempo) --}}
                                                <button type="button" class="btn btn-primary btn-sm btn-confirmar-salida" data-tiempo="{{ $tCancelacion }}">
                                                    <i class="bi bi-door-open"></i> Salida
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                @else
                                    <form action="{{ route('registro.entrada', $alumno->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-arrow-left-circle"></i> Volver
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info border-0 shadow-sm">No hay alumnos registrados en este curso.</div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // --- 1. Lógica del Cronómetro ---
            let faltan = {{ intval($segundosFaltantes) }}; 
            const display = document.getElementById('timer-display');

            if (faltan > 0 && display) {
                const interval = setInterval(() => {
                    faltan--;
                    if (faltan <= 0) {
                        clearInterval(interval);
                        window.location.reload(); 
                    } else {
                        let m = Math.floor(faltan / 60);
                        let s = faltan % 60;
                        display.innerText = (m < 10 ? '0'+m : m) + ':' + (s < 10 ? '0'+s : s);
                    }
                }, 1000);
            }

            // --- 2. Lógica del Ojito de Privacidad ---
            const botonesOjito = document.querySelectorAll('.btn-toggle-ojito');

            botonesOjito.forEach(boton => {
                boton.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    
                    const contenedor = this.closest('div');
                    const numeroReal = contenedor.querySelector('.salidas-numero');
                    const asteriscos = contenedor.querySelector('.salidas-asteriscos');
                    const icono = this.querySelector('i');

                    if (numeroReal.style.display === 'none') {
                        numeroReal.style.display = 'inline';
                        asteriscos.style.display = 'none';
                        icono.classList.remove('bi-eye-slash-fill');
                        icono.classList.add('bi-eye-fill', 'text-primary');
                    } else {
                        numeroReal.style.display = 'none';
                        asteriscos.style.display = 'inline';
                        icono.classList.remove('bi-eye-fill', 'text-primary');
                        icono.classList.add('bi-eye-slash-fill');
                    }
                });
            });

            // --- 3. LÓGICA DEL BOTÓN DE CANCELAR (CUENTA ATRÁS) ---
            document.querySelectorAll('.btn-confirmar-salida').forEach(boton => {
                let timeoutId = null;
                let intervaloId = null;
                let cancelando = false;

                boton.addEventListener('click', function() {
                    const formulario = this.closest('form');
                    const tiempoOriginal = parseInt(this.getAttribute('data-tiempo'));
                    
                    // Si el tiempo está en 0 en la config, envía directo
                    if(tiempoOriginal <= 0) {
                        formulario.submit();
                        return;
                    }

                    let tiempoRestante = tiempoOriginal;

                    if (!cancelando) {
                        // FASE 1: Empieza la cuenta regresiva
                        cancelando = true;
                        this.classList.replace('btn-primary', 'btn-warning');
                        this.innerHTML = `<i class="bi bi-x-circle"></i> Cancelar (${tiempoRestante}s)`;

                        intervaloId = setInterval(() => {
                            tiempoRestante--;
                            if (tiempoRestante > 0) {
                                this.innerHTML = `<i class="bi bi-x-circle"></i> Cancelar (${tiempoRestante}s)`;
                            } else {
                                clearInterval(intervaloId);
                            }
                        }, 1000);

                        timeoutId = setTimeout(() => {
                            // Se acabó el tiempo: Enviamos el formulario
                            this.disabled = true;
                            this.innerHTML = `<i class="bi bi-hourglass-split"></i> Enviando...`;
                            formulario.submit();
                        }, tiempoOriginal * 1000);

                    } else {
                        // FASE 2: El profesor pulsó de nuevo: Se cancela todo
                        clearTimeout(timeoutId);
                        clearInterval(intervaloId);
                        cancelando = false;
                        
                        // Vuelve a su estado azul normal
                        this.classList.replace('btn-warning', 'btn-primary');
                        this.innerHTML = `<i class="bi bi-door-open"></i> Salida`;
                    }
                });
            });

        });
    </script>
</body>
</html>