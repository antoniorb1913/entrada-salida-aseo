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
        .navbar-custom { background-color: #ffffff; border-bottom: 2px solid #dee2e6; }
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 0; }
        .card-step {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            text-decoration: none;
            padding: 50px 20px;
            height: 100%;
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

    <nav class="navbar navbar-custom py-3 shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold">
                <i class="bi bi-door-open me-2 text-primary"></i>Acceso al Baño
            </span>
            <a href="{{ route('acceso.niveles', $etapa) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i> Volver a Niveles
            </a>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Paso 3: Selecciona la Letra/Grupo</h2>
                <p class="text-muted text-uppercase">Curso: <strong>{{ $etapa }} {{ $nivel }}º</strong></p>
            </div>

            <div class="row justify-content-center g-4">
                @foreach($letras as $item)
                    <div class="col-12 col-md-4 col-lg-3">
                        {{-- El enlace usa el ID (ej: 18) y el texto usa la letra (ej: CIENCIA) --}}
                        <a href="{{ route('acceso.alumnos', $item->id) }}" class="card-step bg-warning text-dark shadow text-decoration-none">
                            <i class="bi bi-person-video2"></i>
                            <span class="fw-bold">{{ $item->letra }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

</body>
</html>