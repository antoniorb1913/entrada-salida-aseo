<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<<<<<<< HEAD
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
                    <a href="{{ route('modificar') }}" class="card-btn bg-secondary text-white shadow">
                        <i class="bi bi-pencil-square"></i>
                        <span>Modificar</span>
                    </a>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <a href="{{ route('consultas') }}" class="card-btn bg-info text-white shadow">
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
=======
    <title>Página privada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                <span class="fs-4">Página admin</span>
            </a>
        </header>
    </div>

    <article class="container">
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-3">
                <h2 class="display-5 fw-bold">Tu sección privada</h2>
                <p class="col-md-8 fs-4">Gestión de recursos y controles del sistema.</p>
                
                <hr class="my-4">

                <div class="d-grid gap-2 d-md-flex justify-content-md-start">

                    <a href="{{ route('acceso') }}" class="btn btn-primary btn-lg px-4 me-md-2">
                        Acceso al baño
                    </a>
                
                    <a href="{{ route('modificar') }}" class="btn btn-secondary btn-lg px-4 me-md-2">
                        Modificar
                    </a>
                

                    <a href="{{ route('consultas') }}" class="btn btn-info btn-lg px-4 me-md-2">
                        Consultas
                    </a>
                
                    <a href="{{ route('logout') }}" class="btn btn-danger btn-lg px-4">
                        Salir
                    </a>
                </div>
            </div>
        </div>
    </article>
>>>>>>> 2d9635ba794fef18c5313e37a2cc3942ec6d2898
</body>
</html>