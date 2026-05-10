<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso al Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f7f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background-color: rgb(244, 242, 238);
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            background-color: rgb(78, 94, 113);
            padding: 30px;
            text-align: center;
            color: #ffffff;;
        }
        .login-header i {
            font-size: 3rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25 red rgba(13, 110, 253, 0.15);
        }
        .btn-login {
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .btn-login {
            background-color: rgb(78, 94, 113); /* Tu nuevo color azul oscuro */
            border: none; /* Quitamos el borde para que se vea más limpio */
            color: white; /* Aseguramos que el texto sea blanco */
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: rgb(78, 94, 113); /* Un azul un poco más claro al pasar el ratón */
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 42, 90, 0.3); /* Sombra con el tono de tu azul */
        }

        /* Evita que Bootstrap le ponga el azul claro por defecto al hacer clic */
        .btn-login:active, .btn-login:focus {
            background-color: rgb(78, 94, 113) !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 42, 90, 0.25) !important;
        }
    </style>
</head>
<body>

    <main class="container">
        <div class="login-card mx-auto">
            <div class="login-header">
                <img src="{{ asset('img/BLANCO PNG.png') }}" alt="Logo Gestión de Aseos" style="max-width: 200px;">
                <h4 class="mt-2 mb-0 fw-bold">Gestión de Aseos</h4>
            </div>

            <div class="p-4 p-md-5">
                {{-- Manejo de Errores --}}
                @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm small">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('inicia-sesion') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="nameInput" class="form-label fw-semibold">Nombre de usuario</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-start-0" id="nameInput" name="nombreUsuario" placeholder="Ej: pedro.lopez" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="passwordInput" class="form-label fw-semibold">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" class="form-control bg-light border-start-0" id="passwordInput" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-login">
                            Entrar ahora <i class="bi bi-chevron-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <p class="text-center text-muted mt-4">
            <small>&copy; 2026 IES Antonio Hellín Costa</small>
        </p>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>