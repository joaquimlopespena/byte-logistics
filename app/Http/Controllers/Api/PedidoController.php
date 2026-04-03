<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePedidoApiRequest;
use App\Http\Resources\PedidoCollection;
use App\Http\Resources\PedidoResource;
use App\Service\PedidoApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PedidoController extends Controller
{
    public function __construct(
        private readonly PedidoApiService $pedidoApiService
    ) {}

    public function index(Request $request): JsonResponse|PedidoCollection
    {
        try {
            $perPage = min(max((int) $request->query('per_page', 50), 1), 100);
            $paginator = $this->pedidoApiService->listarPaginado($perPage);

            return new PedidoCollection($paginator);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível listar os pedidos.',
            ], 500);
        }
    }

    public function show(int $id): JsonResponse|PedidoResource
    {
        try {
            $pedido = $this->pedidoApiService->obter($id);

            return new PedidoResource($pedido);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Pedido não encontrado.',
            ], 404);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível consultar o pedido.',
            ], 500);
        }
    }

    public function store(StorePedidoApiRequest $request): JsonResponse
    {
        try {
            $pedido = $this->pedidoApiService->cadastrar($request->validated());
            $pedido->load('transportadora');

            return (new PedidoResource($pedido))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível cadastrar o pedido.',
            ], 500);
        }
    }
}
