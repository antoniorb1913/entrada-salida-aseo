@php
    // Cargamos la configuración centralizada directamente en la vista
    $config = \App\Models\Configuracion::todas();
@endphp

<footer class="mt-5 py-4 text-center w-100">
    <div class="container d-flex flex-column align-items-center gap-3">
        
        {{-- UNIFICADO: ÚNICO AVISO DINÁMICO EN FORMATO CÁPSULA CON FONDO AMARILLO/NARANJA SUAVE --}}
        @if($config->aseo_hombres_disponible == '0' || $config->aseo_hombres_disponible === false || $config->aseo_mujeres_disponible == '0' || $config->aseo_mujeres_disponible === false)
            @php
                $hombresRoto = ($config->aseo_hombres_disponible == '0' || $config->aseo_hombres_disponible === false);
                $mujeresRoto = ($config->aseo_mujeres_disponible == '0' || $config->aseo_mujeres_disponible === false);
            @endphp

            <div class="d-flex align-items-center justify-content-center bg-warning-subtle px-4 py-2 rounded-pill shadow-sm" 
                 style="width: 100%; max-width: 850px;">
                <i class="bi bi-exclamation-triangle-fill text-warning-emphasis fs-5 me-2"></i>
                <span class="text-warning-emphasis" style="font-size: 0.95rem;">
                    <strong>Aviso importante:</strong> 
                    @if($hombresRoto && $mujeresRoto)
                        Los aseos de <span class="text-danger fw-bold">HOMBRES</span> y <span class="text-danger fw-bold">MUJERES</span> se encuentran temporalmente fuera de servicio.
                    @elseif($hombresRoto)
                        El aseo de <span class="text-danger fw-bold">HOMBRES</span> se encuentra temporalmente fuera de servicio.
                    @else
                        El aseo de <span class="text-danger fw-bold">MUJERES</span> se encuentra temporalmente fuera de servicio.
                    @endif
                </span>
            </div>
        @endif

        {{-- RECORDATORIO PRINCIPAL --}}
        <div class="d-flex align-items-center justify-content-center bg-white px-4 py-2 rounded-pill shadow-sm border border-info-subtle" 
             style="width: 100%; max-width: 850px;">
            <i class="bi bi-info-circle-fill text-info fs-5 me-2"></i>
            <span class="text-muted" style="font-size: 0.95rem;">
                <strong>Recordatorio:</strong> El sistema es una herramienta de apoyo. Ante urgencias, siempre <strong>prevalece el sentido común</strong>.
            </span>
        </div>

        {{-- LEYENDA EXCEPCIÓN MÉDICA --}}
        <div class="d-flex align-items-center justify-content-center bg-white px-4 py-2 rounded-pill shadow-sm border border-danger-subtle" 
             style="width: 100%; max-width: 850px;">
            <i class="bi bi-person-heart text-danger fs-5 me-2"></i>
            <span class="text-muted" style="font-size: 0.95rem;">
                Este símbolo indica que el alumno <strong>tiene una excepción médica</strong> para poder salir más veces del límite de salidas.
            </span>
        </div>

        {{-- LEYENDA ACOMPAÑANTE OBLIGATORIO --}}
        <div class="d-flex align-items-center justify-content-center bg-white px-4 py-2 rounded-pill shadow-sm border border-warning-subtle" 
             style="width: 100%; max-width: 850px;">
            <i class="bi bi-person-fill-add text-warning fs-5 me-2"></i>
            <span class="text-muted" style="font-size: 0.95rem;">
                Este símbolo indica que el alumno <strong>requiere un acompañante obligatorio</strong> para poder salir del aula.
            </span>
        </div>

    </div>
</footer>