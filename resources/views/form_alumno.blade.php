<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Filtro Alumno | Instituto</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { 
            background-color: #f4f7f6; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom { 
            background-color: #fff; 
            border-bottom: 2px solid #dee2e6; 
        }
        .main-content { 
            flex: 1; 
            display: flex; 
            align-items: center; 
            padding: 40px 0; 
        }
        .form-card { 
            background-color: #fff; 
            border-radius: 20px; 
            padding: 40px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }
        
        /* Personalización de TomSelect para que encaje con tu diseño */
        .ts-wrapper.form-select-lg .ts-control {
            border-radius: 10px !important;
            padding: 12px 15px !important;
            font-size: 1.1rem !important;
            border: 1px solid #dee2e6;
        }
        .ts-dropdown {
            border-radius: 10px !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom py-3 shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1 text-dark fw-bold"><i class="bi bi-search me-2 text-success"></i>Consultas</span>
            <a href="{{ route('registros') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="form-card text-center">
                        <i class="bi bi-person-badge text-warning mb-3" style="font-size: 4rem;"></i>
                        <h3 class="fw-bold text-secondary mb-2">Filtro por Alumno/a</h3>
                        <p class="text-muted mb-4">Escribe el nombre o apellidos para filtrar los resultados</p>
                        
                        <form action="{{ route('registros.resultados') }}" method="GET">
                            <div class="mb-4 text-start">
                                <label class="small fw-bold text-muted mb-2 ms-1 text-uppercase">Listado de Alumnos</label>
                                
                                <select id="buscador-alumnos" name="alumno_id" class="form-select-lg" required>
                                    <option value=""></option>
                                    @foreach($alumnos as $alumno)
                                        <option value="{{ $alumno->id }}">{{ $alumno->apellidos }} ,{{ $alumno->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold px-5 w-100 shadow-sm mt-2">
                                <i class="bi bi-file-earmark-text me-2"></i>Ver Registros
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect("#buscador-alumnos", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                allowEmptyOption: true,
                maxOptions: 100, // Aumentado para institutos
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results py-2 px-3 text-danger small">No se encontró al alumno "' + escape(data.input) + '"</div>';
                    }
                },
                placeholder: "Escriba nombre del alumno...",
            });
        });
    </script>
</body>
</html>