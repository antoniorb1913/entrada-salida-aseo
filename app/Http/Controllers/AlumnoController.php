<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\AlumnoService;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AlumnoController extends Controller
{
    protected $alumnoservice;

    public function __construct(AlumnoService $alumnoservice) {
        $this->alumnoservice = $alumnoservice;
    }

    public function getAllAlum() {
        $alums = $this->alumnoservice->getAllAlum();
        return ApiResponse::success($alums, "success", Response::HTTP_OK);
    }
}
