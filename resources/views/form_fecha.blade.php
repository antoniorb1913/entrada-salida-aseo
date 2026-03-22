<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Filtro por Fecha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body{background-color:#f4f7f6;min-height:100vh;display:flex;flex-direction:column;}
        .navbar-custom{background-color:#fff;border-bottom:2px solid #dee2e6;}
        .main-content{flex:1;display:flex;align-items:center;padding:40px 0;}
        .form-card{background-color:#fff;border-radius:20px;padding:40px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}
    </style>
</head>
<body>
    <nav class="navbar navbar-custom py-3 shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold"><i class="bi bi-search me-2 text-danger"></i>Consultas</span>
            <a href="{{ route('registros') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver a Filtros</a>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="form-card text-center">
                        <i class="bi bi-calendar-range text-danger mb-3" style="font-size: 4rem;"></i>
                        <h3 class="fw-bold text-secondary mb-2">Filtro por Fecha</h3>
                        <p class="text-muted mb-4">Selecciona el rango de fechas para consultar los registros.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger text-start border-0 shadow-sm mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('registros.resultados') }}" method="GET">
                            <div class="row text-start mb-4">
                                <div class="col-12 col-sm-6 mb-3 mb-sm-0">
                                    <label class="form-label fw-bold text-secondary">Desde:</label>
                                    <input type="date" name="fecha_inicio" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label fw-bold text-secondary">Hasta:</label>
                                    <input type="date" name="fecha_fin" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger btn-lg rounded-pill fw-bold px-5 w-100 shadow-sm">
                                <i class="bi bi-search me-2"></i>Ver Registros
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>