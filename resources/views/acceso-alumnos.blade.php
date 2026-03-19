@php
    // Usamos el valor que viene del controlador o 300 (5 min) por defecto
    $tiempoEspera = $tiempoEsperaSegundos ?? 300; 
    $segundosFaltantes = 0;
    $ultimaSalida = null;
    
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

    {{-- 1. QUITAMOS el meta refresh (para que no parpadee la página) --}}

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
    </style>
</head>
<body>
    <nav class="navbar bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <span class="fw-bold"><i class="bi bi-person-badge me-2"></i> {{ $curso->etapas }} {{ $curso->nivel }} {{ $curso->letra }}</span>
            <a href="{{ route('acceso.letras', ['etapa' => $curso->etapas, 'nivel' => $curso->nivel]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center mb-3 fw-bold">Selecciona al Alumno</h2><br><br>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 2. EL CARTEL (Añadimos un ID al span del tiempo) --}}
        @if($segundosFaltantes > 0)
            <div class="text-center mb-4">
                <span class="badge bg-primary text-white fs-4 p-3 shadow-sm rounded-pill">
                    <i class="bi bi-stopwatch me-1"></i> Siguiente salida en: 
                    <span id="timer-display">{{ gmdate('i:s', $segundosFaltantes) }}</span>
                </span>
            </div><br><br>
        @endif

        <div class="row g-3">
            @forelse($alumnos as $alumno)
                @php
                    $registroActivo = $alumno->registros->where('estado', \App\Enums\Estado::FUERA)->first();
                    $salidasHoy = $alumno->registros->filter(function($reg) {
                        return \Carbon\Carbon::parse($reg->fecha_salida)->isToday();
                    })->count();
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card student-card shadow-sm p-3 {{ $registroActivo ? 'on-break' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $alumno->apellidos }}, {{ $alumno->nombre }}</h6>
                                
                                @if($salidasHoy >= 3)
                                    <span class="text-danger" style="font-weight: bold; font-size: 0.9em;">Has alcanzado el límite</span>
                                @else
                                    <span class="text-muted" style="font-size: 0.9em;">Salidas hoy: {{ $salidasHoy }}</span>
                                @endif

                                @if($registroActivo)
                                    <br><small class="text-danger fw-bold"><i class="bi bi-dot"></i> En el baño</small>
                                @endif
                            </div>

                            @if(!$registroActivo)
                                @if($salidasHoy < 3)
                                    <form action="{{ route('registro.salida', $alumno->id) }}" method="POST">
                                        @csrf
                                        @if($segundosFaltantes > 0)
                                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                <i class="bi bi-hourglass-split"></i> Espera
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-megaphone"></i> Salida
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
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info">No hay alumnos registrados en este curso.</div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- 3. JAVASCRIPT SIMPLE --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let faltan = {{ intval($segundosFaltantes) }}; // Pasamos los segundos de PHP a JS
            const display = document.getElementById('timer-display');

            if (faltan > 0 && display) {
                const interval = setInterval(() => {
                    faltan--;
                    
                    if (faltan <= 0) {
                        clearInterval(interval);
                        window.location.reload(); // Recarga solo cuando llega a 0 para activar botones
                    } else {
                        // Formatear minutos y segundos
                        let m = Math.floor(faltan / 60);
                        let s = faltan % 60;
                        display.innerText = (m < 10 ? '0'+m : m) + ':' + (s < 10 ? '0'+s : s);
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>