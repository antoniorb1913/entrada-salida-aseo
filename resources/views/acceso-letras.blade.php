<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seleccionar Letra - {{ $etapa }} {{ $nivel }}º</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column; }
        @media (max-width: 576px) {
            body {
                padding-top: 20%;
            }
        }
        .navbar-custom { background-color: #ffffff; border-bottom: 2px solid #dee2e6; }
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; }
        .card-step {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            text-decoration: none;
            padding: 20px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .card-step:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        .card-step i { font-size: 3.5rem; margin-bottom: 15px; }
        .card-step span { font-size: 1.5rem; font-weight: 700; }
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
    
            <a href="{{ route('acceso.niveles', [$etapa, $modalidad]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
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

    <main class="main-content">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Paso 4: Selecciona la Letra/Grupo</h2>
                @if(str_contains(strtolower($etapa), 'eso'))

                <p class="text-muted text-uppercase">
                    Curso: <strong>{{ $nivel }}</strong>
                </p>
            @else
                <p class="text-muted text-uppercase">
                    Curso: <strong>{{ $nivel }} {{ $modalidad }}</strong>
                </p>
            @endif
            </div>

            <div class="row justify-content-center g-4">
                @foreach($letras as $item)
                    <div class="col-12 col-md-4 col-lg-3">
                        <a href="{{ route('acceso.alumnos', $item->id) }}" class="card-step bg-warning text-dark shadow text-decoration-none">
                            <i class="bi bi-person-video2"></i>
                            {{-- Usamos nl2br para que el \n del Seeder se convierta en un intro real --}}
                            <span class="fw-bold" style="font-size: 2.0rem; line-height: 1.1;">
                                {!! nl2br(e($item->letra)) !!}
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

</body>
</html>