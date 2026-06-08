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
        body { background-color:rgb(244, 242, 238);min-height:50vh;display:flex;flex-direction:column;padding-top: 5%; }
        @media (max-width: 576px) {
            body {
                padding-top: 20%;
            }
        }

        .navbar-custom { background-color: rgb(253, 252, 249); border-bottom: 2px solid #dee2e6; }
        .nav-color { background-color: rgb(246, 246, 244); }

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
        
        /* Color base del botón */
        .salida-color {
            background-color: rgb(88, 127, 175) !important;
            color: white !important;
            border: none;
        }
        .salida-color:hover, 
        .salida-color:active, 
        .salida-color:focus {
            background-color: rgb(88, 127, 175) !important; 
            color: white !important;
            transform: none !important;
            box-shadow: none !important;
        }
        .contador-color { background-color: rgb(68, 122, 187); }
        .activo { color: #278943; }

        .btn-fijo {
            width: 105px !important;
            height: 55px !important;
            display: flex !important;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0;
            line-height: 1.2;
            font-size: 0.9rem;
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
    
            <div class="d-flex gap-2">
                @php
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
                
                <a href="{{ $urlVolver }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-2"></i> Volver
                </a>
            </div>
        </div>
    
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

    <div class="container mb-5 mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-1">Selecciona al Alumno</h2>
            <span class="fw-bold text-muted fs-5">
                <i class="bi bi-layers me-2 text-primary"></i> 
                {{ $curso->nivel }} {{ $curso->letra ?? '' }} {{ $curso->modalidad }}
            </span>

            <div class="mt-3 text-center">
                <span class="badge salida-color fs-6 p-2 px-3 rounded-pill">
                    <i class="bi bi-people-fill me-2"></i>Alumnos fuera: <strong>{{ $aforo->total }}</strong>
                </span>
            </div>
        </div>

        {{-- UNIFICADO: ÚNICO AVISO DINÁMICO DE ASEOS OPERATIVOS --}}
        @if($config->aseo_hombres_disponible == '0' || $config->aseo_hombres_disponible === false || $config->aseo_mujeres_disponible == '0' || $config->aseo_mujeres_disponible === false)
        @php
            $hombresRoto = ($config->aseo_hombres_disponible == '0' || $config->aseo_hombres_disponible === false);
            $mujeresRoto = ($config->aseo_mujeres_disponible == '0' || $config->aseo_mujeres_disponible === false);
        @endphp

        <div class="container mt-4 mb-4">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="alert alert-warning d-flex align-items-center shadow-sm border border-warning-subtle py-2 px-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-5 me-2"></i>
                        <div class="text-muted" style="font-size: 0.9rem;">
                            <strong class="text-dark">Aviso importante:</strong> 
                            
                            @if($hombresRoto && $mujeresRoto)
                                Los aseos de <span class="text-danger fw-bold">HOMBRES</span> y <span class="text-danger fw-bold">MUJERES</span> se encuentran temporalmente fuera de servicio.
                            @elseif($hombresRoto)
                                El aseo de <span class="text-danger fw-bold">HOMBRES</span> se encuentra temporalmente fuera de servicio.
                            @else
                                El aseo de <span class="text-danger fw-bold">MUJERES</span> se encuentra temporalmente fuera de servicio.
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($segundosFaltantes > 0)
            <div class="text-center mb-5">
                <span class="badge contador-color text-white fs-4 p-3 shadow-sm rounded-pill">
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
                                        
                                        {{-- Icono de Acompañante Obligatorio --}}
                                        @if($alumno->necesita_tutor)
                                            <span class="ms-2 text-warning" title="Requiere acompañante obligatorio" style="cursor: help;">
                                                <i class="bi bi-person-fill-add fs-5"></i>
                                            </span>
                                        @endif

                                        {{-- Icono de Excepción de Límite (Excepción Médica) --}}
                                        @if($alumno->excepcion_limite)
                                            <span class="ms-2 text-danger" title="Excepción médica (Sin límite de salidas)" style="cursor: help;">
                                                <i class="bi bi-person-heart fs-5"></i>
                                            </span>
                                        @endif
                                        
                                    </div>
                                @endif

                                @if($registroActivo)
                                    <div class="mt-1"><small class="text-danger fw-bold"><i class="bi bi-dot"></i> En el baño</small></div>
                                @endif
                            </div>

                            <div class="ms-2">
                                @if(!$registroActivo)
                                    @if(!$limiteAlcanzado)
                                        <form action="{{ route('registro.salida', $alumno->id) }}" method="POST" class="form-salida">
                                            @csrf
                                            @if($segundosFaltantes > 0)
                                                {{-- Aplicamos btn-fijo --}}
                                                <button type="button" class="btn btn-secondary btn-sm btn-fijo" disabled>
                                                    <div><i class="bi bi-hourglass-split"></i> Espera</div>
                                                </button>
                                            @else
                                                {{-- Aplicamos btn-fijo --}}
                                                <button type="button" class="btn salida-color btn-sm btn-confirmar-salida btn-fijo" data-tiempo="{{ $tCancelacion }}">
                                                    <div><i class="bi bi-door-open"></i> Salida</div>
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                @else
                                    <form action="{{ route('registro.entrada', $alumno->id) }}" method="POST">
                                        @csrf
                                        {{-- Aplicamos btn-fijo --}}
                                        <button type="submit" class="btn btn-success btn-sm btn-fijo">
                                            <div><i class="bi bi-arrow-left-circle"></i> Volver</div>
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

    @include('Footer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ==========================================
            // --- 1. LÓGICA DEL CRONÓMETRO GLOBAL ---
            // ==========================================
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

            // ==========================================
            // --- 2. LÓGICA DEL OJITO DE PRIVACIDAD ---
            // ==========================================
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

            // ==========================================
            // --- 3. LÓGICA DEL BOTÓN CANCELAR SALIDA ---
            // ==========================================
            document.querySelectorAll('.btn-confirmar-salida').forEach(boton => {
                let timeoutId = null;
                let intervaloId = null;
                let cancelando = false;

                boton.addEventListener('click', function() {
                    const formulario = this.closest('form');
                    const tiempoOriginal = parseInt(this.getAttribute('data-tiempo'));
                    
                    if(tiempoOriginal <= 0) {
                        formulario.submit();
                        return;
                    }

                    let tiempoRestante = tiempoOriginal;

                    if (!cancelando) {
                        cancelando = true;
                        this.classList.replace('salida-color', 'btn-warning'); 
                        this.innerHTML = `<div><i class="bi bi-x-circle"></i> Cancelar</div><small class="fw-bold">(${tiempoRestante}s)</small>`;

                        intervaloId = setInterval(() => {
                            tiempoRestante--;
                            if (tiempoRestante > 0) {
                                this.innerHTML = `<div><i class="bi bi-x-circle"></i> Cancelar</div><small class="fw-bold">(${tiempoRestante}s)</small>`;
                            } else {
                                clearInterval(intervaloId);
                            }
                        }, 1000);

                        timeoutId = setTimeout(() => {
                            this.disabled = true;
                            this.innerHTML = `<div><i class="bi bi-hourglass-split"></i> Enviando</div>`;
                            formulario.submit();
                        }, tiempoOriginal * 1000);

                    } else {
                        clearTimeout(timeoutId); 
                        clearInterval(intervaloId); 
                        cancelando = false;
                        
                        this.classList.replace('btn-warning', 'salida-color');
                        this.innerHTML = `<div><i class="bi bi-door-open"></i> Salida</div>`;
                    }
                });
            });
        });
    </script>
</body>
</html>