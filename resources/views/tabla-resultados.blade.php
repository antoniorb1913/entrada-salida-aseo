<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultados del Filtro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {background-color:#f4f7f6;min-height:50vh;display:flex;flex-direction:column;padding-top: 5%}
        .table-card { 
            background-color: #ffffff; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            overflow: hidden; 
            border: none; 
        }
        .table thead th { 
            background-color: #ffc107; 
            color: #212529; 
            text-transform: uppercase; 
            border-bottom: none; 
            padding: 15px; 
            font-size: 0.85rem; 
            letter-spacing: 1px; 
        }
        .table tbody td { 
            padding: 15px; 
            vertical-align: middle; 
        }
        .avatar-circle { 
            width: 35px; 
            height: 35px; 
            background-color: #eee; 
            border-radius: 50%; 
            display: inline-flex; 
            align-items: center; 
            justify-content: 
            center; 
            margin-right: 10px; 
            font-weight: bold; 
            color: #666; 
        }
        .navbar-custom{
            background-color:#fff;
            border-bottom: 2px solid #dee2e6;
        }
        .main-content{
            flex: 1;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        /* Estilo para centrar y dar espacio a la paginación */
        .paginacion-centrada nav > div {
            display: flex;
            align-items: center;
            gap: 15px;
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
    
            <a href="{{ route('registros') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    
        {{-- Franja inferior estrecha: Nombre con monigote verde "Online" --}}
        <div class="w-100 border-top mt-2 pt-1 pb-1 bg-light">
            <div class="container text-center">
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    {{-- Icono en verde success para indicar sesión activa --}}
                    <i class="bi bi-person-circle me-1 text-success"></i>
                    Sesión de: {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}
                </small>
            </div>
        </div>
    </nav>
    <main class="main-content mt-5 pt-5">
        <div class="container mb-5">
            <div class="table-card p-3">
                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('consulta.exportar', request()->query()) }}" class="btn btn-success shadow-sm rounded-pill px-4">
                        <i class="bi bi-file-earmark-excel me-2"></i>Exportar a Excel
                    </a>
                </div>
                <div class="table-responsive">  
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Alumno/a</th>
                                <th>Curso</th>
                                <th>Profesor/a</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Salida</th>
                                <th class="text-center">Entrada</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registros as $reg)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-bold">{{ $reg->alumno->apellidos ?? 'Sin Apellido' }}, {{ $reg->alumno->nombre ?? 'Sin Nombre' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $reg->curso->nivel ?? '' }} {{ $reg->curso->letra ?? '' }} {{ $reg->curso->modalidad ?? '' }}</span></td>
                                    
                                    {{-- ARREGLO AQUÍ: El modelo User suele tener 'name', no 'nombre' y 'apellidos' por defecto --}}
                                    <td>
                                        <i class="bi bi-person-workspace me-1 text-muted"></i>
                                        {{ $reg->profesor->apellidos ?? 'No asignado' }}, {{ $reg->profesor->nombre ?? 'No asignado' }}
                                    </td>

                                    <td class="text-center small text-secondary">
                                        {{ \Carbon\Carbon::parse($reg->fecha_salida)->format('d/m/Y') }}
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border font-monospace">
                                            {{ \Carbon\Carbon::parse($reg->fecha_salida)->format('H:i') }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if($reg->fecha_entrada)
                                            <span class="badge bg-light text-dark border font-monospace">{{ \Carbon\Carbon::parse($reg->fecha_entrada)->format('H:i') }}</span>
                                        @else
                                            <span class="text-muted small">--:--</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if($reg->estado->value === 'fuera')
                                            <span class="badge rounded-pill bg-danger px-3">FUERA</span>
                                        @else
                                            <span class="badge rounded-pill bg-success px-3">EN CLASE</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-clipboard-x text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                                            <h5 class="fw-bold text-secondary">No se han encontrado registros</h5>
                                            <p class="text-muted">Prueba a seleccionar otro filtro o rango de fechas.</p>
                                            <a href="{{ route('registros') }}" class="btn btn-outline-secondary btn-sm rounded-pill mt-2">Limpiar búsqueda</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
            {{-- Paginación centrada sin texto en inglés --}}
            @if($registros->hasPages())
            <div class="mt-4 pb-2">
                {{-- Añadimos la clase 'gap-4' para dar espacio vertical entre el texto y los números --}}
                <div class="paginacion-centrada d-flex justify-content-center text-center gap-4">
                    {{ $registros->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
            </div>
        </div>
    </main>
</body>
</html>