<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RemoteHubService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.hub.url');
        $this->token = config('services.hub.api_token');
    }

    // 1. Obtener etapas únicas (sin parámetros)
    public function getEtapasUnicas()
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . "/api/etapas")
            ->json();

           
    }

    // 2. Obtener modalidades filtradas por etapa (USANDO QUERY PARAM)
    public function getModalidadesPorEtapa($etapa)
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . "/api/modalidades", [
                'etapa' => $etapa
            ])
            ->json();
    }

    // 3. Obtener niveles filtrados por etapa y modalidad (USANDO QUERY PARAMS)
    public function getNivelesPorEtapa($etapa, $modalidad)
    {
        $prueba =  Http::withToken($this->token)
            ->get($this->baseUrl . "/api/niveles", [
                'etapa' => $etapa,
                'modalidad' => $modalidad
            ])
            ->json();

            var_dump($prueba);
            exit;
    }

    // 4. Obtener letras (grupos) por nivel
    public function getLetrasPorNivel($etapa, $modalidad, $nivel)
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . "/api/letras", [
                'etapa' => $etapa,
                'modalidad' => $modalidad,
                'nivel' => $nivel
            ])
            ->json();
    }

    // 5. Obtener alumnos por curso específico
    public function getAlumnosPorCurso($curso_id)
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . "/api/cursos/{$curso_id}/alumnos")
            ->json();
    }
}