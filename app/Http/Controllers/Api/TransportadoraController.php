<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransportadoraCollection;
use App\Http\Resources\TransportadoraResource;
use App\Service\TransportadoraApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TransportadoraController extends Controller
{
    public function __construct(
        private readonly TransportadoraApiService $transportadoraApiService
    ) {}

    public function index(Request $request): JsonResponse|TransportadoraCollection
    {
        try {
            $perPage = min(max((int) $request->query('per_page', 50), 1), 100);
            $paginator = $this->transportadoraApiService->listarPaginado($perPage);

            return new TransportadoraCollection($paginator);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível listar as transportadoras.',
            ], 500);
        }
    }

    public function show(int $id): JsonResponse|TransportadoraResource
    {
        try {
            $transportadora = $this->transportadoraApiService->obter($id);

            return new TransportadoraResource($transportadora);
        } catch (ModelNotFoundException $e) {
            report($e);

            return response()->json([
                'message' => 'Transportadora não encontrada.',
            ], 404);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível consultar a transportadora.',
            ], 500);
        }
    }

    public function all(): JsonResponse|TransportadoraCollection
    {
        try {
            $transportadoras = $this->transportadoraApiService->listarTodas();

            return new TransportadoraCollection($transportadoras);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível listar as transportadoras.',
            ], 500);
        }
    }
}
