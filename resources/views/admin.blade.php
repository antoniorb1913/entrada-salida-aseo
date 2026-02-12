<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</body>
</html>