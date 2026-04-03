<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransportadoraResource;
use App\Service\TransportadoraApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class TransportadoraController extends Controller
{
    public function __construct(
        private readonly TransportadoraApiService $transportadoraApiService
    ) {}

    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            $paginator = $this->transportadoraApiService->listarPaginado($request->query('per_page', 10));

            return TransportadoraResource::collection($paginator);
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

    public function all(): JsonResponse|AnonymousResourceCollection
    {
        try {
            $transportadoras = $this->transportadoraApiService->listarTodas();

            return TransportadoraResource::collection($transportadoras);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível listar as transportadoras.',
            ], 500);
        }
    }
}
