<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    <main class="container align-center p-5">
        {{-- Comprobamos si la variable global $errors contiene algún mensaje de validación --}}
        @if ($errors->any())
        {{-- Contenedor visual de Bootstrap para alertas de error --}}
        <div class="alert alert-danger">            
            <ul class="mb-0">
                {{-- Recorremos la lista de todos los errores para mostrarlos uno a uno --}}
                @foreach ($errors->all() as $error)
                    {{-- Mostramos los mensajes en una lista de puntos --}}
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form method="POST" action="{{ route('inicia-sesion') }}">
            @csrf
            <div class="mb-3">
                <label for="nameInput" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nameInput" name="nombre" required>
            </div>

            <div class="mb-3">
                <label for="passwordInput" class="form-label">Password</label>
                <input type="password" class="form-control" id="passwordInput" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Acceder</button>
        </form>
    </main>
</body>
</html>