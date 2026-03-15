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
        /* Resaltado visual cuando están fuera */
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
            
            {{-- ARREGLO 1: Botón volver con ruta real de Laravel para evitar el problema del historial --}}
            <a href="{{ route('acceso.letras', ['etapa' => $curso->etapas, 'nivel' => $curso->nivel]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center mb-4 fw-bold">Selecciona al Alumno</h2>
        
        {{-- ARREGLO 2: Alerta roja para los mensajes de error (tiempo de espera o límite de salidas) --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3">
            @forelse($alumnos as $alumno)
                @php
                    $registroActivo = $alumno->registros->where('estado', \App\Enums\Estado::FUERA)->first();
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card student-card shadow-sm p-3 {{ $registroActivo ? 'on-break' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $alumno->apellidos }}, {{ $alumno->nombre }}</h6>
                                
                                @php
                                    $salidasHoy = $alumno->registros->filter(function($reg) {
                                        return \Carbon\Carbon::parse($reg->fecha_salida)->isToday();
                                    })->count();
                                @endphp
                                <small class="text-muted d-block">Salidas hoy: <strong>{{ $salidasHoy }}</strong></small>

                                {{-- Indicador de texto rojo mantenido --}}
                                @if($registroActivo)
                                    <small class="text-danger fw-bold"><i class="bi bi-dot"></i> En el baño</small>
                                @endif
                            </div>

                            @if(!$registroActivo)
                                <form action="{{ route('registro.salida', $alumno->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-megaphone"></i> Salida
                                    </button>
                                </form>
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
</body>
</html>