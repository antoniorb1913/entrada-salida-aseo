<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seleccionar Nivel - {{ $etapa }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: rgb(244, 242, 238); min-height: 100vh; display: flex; flex-direction: column; }
        @media (max-width: 576px) {
        body {
            padding-top: 10%;
        }
    }
        .navbar-custom { background-color: rgb(253, 252, 249); border-bottom: 2px solid #dee2e6; }
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
        .nav-color {
            background-color: rgb(246, 246, 244);
        }
        .nivel-color {
            background-color: #3d6851;
        }
        .card-step:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        .card-step i { font-size: 3.5rem; margin-bottom: 15px; }
        .card-step span { font-size: 1.5rem; font-weight: 700; }
        .activo {
            color: #278943;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom py-2 shadow-sm fixed-top">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold">
                <i class="bi bi-door-open me-2 text-primary"></i>Control salidas al aseo
            </span>
    
            <a href="{{ route('acceso') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    
        {{-- Franja inferior estrecha para el nombre del usuario --}}
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

    <main class="main-content mt-5 pt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Paso 3: Selecciona el Curso</h2>
                
                @if(str_contains(strtolower($etapa), 'eso'))
                    {{-- Si es ESO, mostramos solo la etapa y el nivel --}}
                    <p class="text-muted text-uppercase">
                        Etapa seleccionada: <strong>{{ $etapa }}</strong>
                    </p>
                @else
                    {{-- Si es Bachillerato o FP, mostramos el nivel y la modalidad --}}
                    <p class="text-muted text-uppercase">
                        modalidad: <strong>{{ $modalidad }}</strong>
                    </p>
                @endif
            </div>

            <div class="row justify-content-center g-4">
                {{-- Recorremos los niveles que el controlador envía (ej: 1, 2) --}}
                @foreach($niveles as $nivel)
                    <div class="col-12 col-md-4 col-lg-3">
                        {{-- Usamos color btn-success para diferenciar que es el paso 2 --}}
                        <a href="{{ route('acceso.letras', [$etapa, $modalidad, $nivel]) }}" class="card-step nivel-color text-white shadow text-decoration-none">
                            <i class="bi bi-layers"></i>
                            <span class="fw-bold">{{ $nivel }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

</body>
</html>