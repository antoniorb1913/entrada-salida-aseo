<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<<<<<<< HEAD
    <title>Panel de Consultas</title>
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
                <i class="bi bi-search-heart me-2"></i>Página Consulta
            </span>
            <div class="d-flex gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary d-flex align-items-center">
                    <i class="bi bi-arrow-left me-2"></i> Volver
                </a>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger d-flex align-items-center">
                    <i class="bi bi-box-arrow-right me-2"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-secondary">Sección de Consultas y Reportes</h2>
                <p class="text-muted">Selecciona una opción para visualizar los registros</p>
            </div>

            <div class="row justify-content-center g-4">
                
                <div class="col-12 col-md-5 col-lg-4">
                    <a href="{{ route('consultas') }}" class="card-btn bg-info text-white shadow">
                        <i class="bi bi-table"></i>
                        <span>Ver Historial General</span>
                    </a>
                </div>

                <div class="col-12 col-md-5 col-lg-4">
                    <a href="#" class="card-btn bg-dark text-white shadow">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Estadísticas de Uso</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

    <footer class="text-center py-4 text-muted mt-auto">
        <small>Sistema de Gestión de Aseos &copy; 2026</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
=======
    <title>Página privada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                Página consulta
            </a>

            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            
                <a href="{{ route('consultas') }}" class="btn btn-info btn-lg px-4 me-md-2">
                    Consultas
                </a>
            
                <a href="{{ route('logout') }}" class="btn btn-danger btn-lg px-4">
                    Salir
                </a>
            </div>
        </header>
    </div>
    <article class="container">
        <h2>Tu sección privada</h2>
    </article>
>>>>>>> 2d9635ba794fef18c5313e37a2cc3942ec6d2898
</body>
</html>