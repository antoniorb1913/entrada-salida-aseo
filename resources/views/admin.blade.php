<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f7f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 2px solid #dee2e6;
        }
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        .card-btn {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            text-decoration: none;
            padding: 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .card-btn:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        .card-btn i {
            font-size: 4rem;
            margin-bottom: 15px;
        }
        .card-btn span {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom py-3 shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold">
                <i class="bi bi-shield-lock me-2 text-primary"></i>Página Admin
            </span>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger d-flex align-items-center">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
            </a>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Panel de Control General</h2>
                <p class="text-muted">Gestión de recursos y controles del sistema</p>
            </div>

            <div class="row justify-content-center g-4">
                
                <div class="col-12 col-md-4 col-lg-3">
                    <a href="{{ route('acceso') }}" class="card-btn bg-primary text-white shadow">
                        <i class="bi bi-door-open"></i>
                        <span>Acceso al Baño</span>
                    </a>
                </div>

<div class="col-12 col-md-4 col-lg-3">
                    <a href="{{ route('configuracion.index') }}" class="card-btn bg-secondary text-white shadow">
                        <i class="bi bi-gear-fill"></i>
                        <span>Configuración</span>
                    </a>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <a href="{{ route('registros') }}" class="card-btn bg-info text-white shadow">
                        <i class="bi bi-journal-text"></i>
                        <span>Consultas</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

    <footer class="text-center py-4 text-muted mt-auto">
        <small>Sistema de Gestión de Aseos &copy; 2026</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>