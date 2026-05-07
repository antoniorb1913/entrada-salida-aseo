@php
    // 1. Cargamos el "pack" de configuración centralizado
    $config = \App\Models\Configuracion::todas();
    
    // Asignamos las variables que ya usa el resto de tu HTML
    $maxSalidas = $config->max_salidas;
    $tiempoEspera = $config->tiempo_espera; 
    
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
        body { background-color: #f4f7f6; }
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
        /* Estrellita para las excepciones médicas */
        .excepcion-badge { 
            position: absolute; 
            top: -10px; 
            right: -10px; 
            font-size: 1.4rem; 
            color: #ffc107; 
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <span class="fw-bold"><i class="bi bi-person-badge me-2"></i> {{ $curso->etapas }} {{ $curso->nivel }} {{ $curso->letra }}</span>
            <a href="{{ route('acceso.letras', ['etapa' => $curso->etapas, 'modalidad' => $curso->modalidad ?? 'comun', 'nivel' => $curso->nivel]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </nav>

    <div class="container mb-5">
        <h2 class="text-center mb-5 fw-bold">Selecciona al Alumno</h2>

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

                    // 2. LA LÓGICA MAESTRA: Ha llegado al límite SOLO SI (no tiene excepción Y tiene >= salidas maximas)
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
                                        <form action="{{ route('registro.salida', $alumno->id) }}" method="POST">
                                            @csrf
                                            @if($segundosFaltantes > 0)
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                    <i class="bi bi-hourglass-split"></i> Espera
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-primary btn-sm">
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
                        // Mostrar número
                        numeroReal.style.display = 'inline';
                        asteriscos.style.display = 'none';
                        icono.classList.remove('bi-eye-slash-fill');
                        icono.classList.add('bi-eye-fill', 'text-primary');
                    } else {
                        // Ocultar número
                        numeroReal.style.display = 'none';
                        asteriscos.style.display = 'inline';
                        icono.classList.remove('bi-eye-fill', 'text-primary');
                        icono.classList.add('bi-eye-slash-fill');
                    }
                });
            });

        });
    </script>
</body>
</html>