<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultados del Filtro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; padding-top: 40px; }
        .table-card { background-color: #ffffff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
        .table thead th { background-color: #ffc107; color: #212529; text-transform: uppercase; border-bottom: none; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container mb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-secondary"><i class="bi bi-list-check me-2"></i>Registros de Salidas</h2>
            {{-- Este botón te devuelve a TUS botones amarillos --}}
            <a href="{{ route('registros') }}" class="btn btn-dark shadow-sm rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Volver a Filtros
            </a>
        </div>

        <div class="table-card p-3">
            <div class="table-responsive">  
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Alumno/a</th>
                            <th>Curso</th>
                            <th>Profesor/a</th>
                            <th>Fecha</th>
                            <th>Salida</th>
                            <th>Entrada</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $reg)
                            <tr>
                                <td class="fw-bold">{{ $reg->alumno->apellidos ?? '' }}, {{ $reg->alumno->nombre ?? '' }}</td>
                                <td>{{ $reg->curso->etapas ?? '' }} {{ $reg->curso->nivel ?? '' }} {{ $reg->curso->letra ?? '' }}</td>
                                {{-- Busca la celda del profesor y asegúrate de que ponga esto --}}
                                <td>{{ $reg->profesor->apellidos ?? 'No asignado' }}, {{ $reg->profesor->nombre ?? 'No asignado' }} </td>
                                <td>{{ \Carbon\Carbon::parse($reg->fecha_salida)->format('d/m/Y') }}</td>
                                <td><span class="badge bg-light text-dark border">{{ \Carbon\Carbon::parse($reg->fecha_salida)->format('H:i') }}</span></td>
                                <td>
                                    @if($reg->fecha_entrada)
                                        <span class="badge bg-light text-dark border">{{ \Carbon\Carbon::parse($reg->fecha_entrada)->format('H:i') }}</span>
                                    @else
                                        <span class="text-muted">---</span>
                                    @endif
                                </td>
                                <td>
                                    @if($reg->estado->value === 'FUERA')
                                        <span class="badge bg-danger">En el baño</span>
                                    @else
                                        <span class="badge bg-success">Volvió</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-folder-x text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="fw-bold text-secondary">No hay datos</h5>
                                    <p class="text-muted">No encontramos salidas al baño con este filtro.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $registros->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</body>
</html>