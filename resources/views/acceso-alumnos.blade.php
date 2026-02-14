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
            border: none;
        }
        .student-card:hover { 
            background-color: #e9ecef; 
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <nav class="navbar bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <span class="fw-bold"><i class="bi bi-person-badge me-2"></i> {{ $curso->etapas }} {{ $curso->nivel }}º {{ $curso->letra }}</span>
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center mb-4 fw-bold">Selecciona al Alumno</h2>
        <div class="row g-3">
            @forelse($alumnos as $alumno)
                <div class="col-md-6 col-lg-4">
                    <div class="card student-card shadow-sm p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $alumno->apellidos }}, {{ $alumno->nombre }}</h6>
                            </div>
                            {{-- Aquí irá el botón para registrar la salida al baño --}}
                            <button class="btn btn-primary btn-sm">
                                <i class="bi bi- megaphone"></i> Salida
                            </button>
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
</body>
</html>