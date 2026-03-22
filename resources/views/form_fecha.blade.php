<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Filtro Fecha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body{background-color:#f4f7f6;min-height:100vh;display:flex;flex-direction:column;}
        .navbar-custom{background-color:#fff;border-bottom:2px solid #dee2e6;}
        .main-content{flex:1;display:flex;align-items:center;padding:40px 0;}
        .form-card{background-color:#fff;border-radius:20px;padding:40px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}
        
        /* Estilos para las pestañas rojas */
        .nav-tabs .nav-link { color: #6c757d; font-weight: bold; border-radius: 10px 10px 0 0; }
        .nav-tabs .nav-link:hover { border-color: transparent transparent #dee2e6; }
        .nav-tabs .nav-link.active { color: #dc3545; border-bottom: 3px solid #dc3545; border-top: none; border-left: none; border-right: none; background: transparent; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom py-3 shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold"><i class="bi bi-search me-2 text-danger"></i>Consultas</span>
            <a href="{{ route('registros') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a>
        </div>
    </nav>
    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="form-card text-center">
                        <i class="bi bi-calendar-event text-danger mb-3" style="font-size: 4rem;"></i>
                        <h3 class="fw-bold text-secondary mb-4">Filtro por Fecha</h3>

                        <ul class="nav nav-tabs justify-content-center mb-4" id="fechaTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="dia-tab" data-bs-toggle="tab" data-bs-target="#dia" type="button" role="tab" aria-controls="dia" aria-selected="true">Día Concreto</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rango-tab" data-bs-toggle="tab" data-bs-target="#rango" type="button" role="tab" aria-controls="rango" aria-selected="false">Rango de Fechas</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="fechaTabsContent">
                            
                            <div class="tab-pane fade show active" id="dia" role="tabpanel" aria-labelledby="dia-tab">
                                <form action="{{ route('registros.resultados') }}" method="GET">
                                    <div class="text-start mb-4">
                                        <label class="form-label fw-bold text-secondary">Selecciona el día:</label>
                                        <input type="date" name="fecha" class="form-control form-control-lg" required>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-lg rounded-pill fw-bold px-5 w-100">Ver Registros del Día</button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="rango" role="tabpanel" aria-labelledby="rango-tab">
                                <form action="{{ route('registros.resultados') }}" method="GET">
                                    <div class="row text-start mb-4">
                                        <div class="col-6">
                                            <label class="form-label fw-bold text-secondary">Desde:</label>
                                            <input type="date" name="fecha_inicio" class="form-control form-control-lg" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-bold text-secondary">Hasta:</label>
                                            <input type="date" name="fecha_fin" class="form-control form-control-lg" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-lg rounded-pill fw-bold px-5 w-100">Ver Registros del Rango</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>