<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pedido\StorePedidoRequest;
use App\Http\Requests\Pedido\UpdatePedidoRequest;
use App\Models\Pedido;
use App\Models\Transportadora;
use App\Service\FiltroPedidoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $pedidos = (new FiltroPedidoService)->filtrarPedidos($request->all())->paginate(50);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $transportadoras = Transportadora::all();

        return view('admin.pedidos.create', compact('transportadoras'));
    }

    public function store(StorePedidoRequest $request)
    {
        $validated = $request->validated();
        DB::transaction(function () use ($validated) {
            Pedido::create([
                'descricao' => $validated['descricao'],
                'cliente_nome' => $validated['nome_cliente'],
                'produto' => $validated['produto'],
                'preco' => $validated['preco'],
                'quantidade' => $validated['quantidade'],
                'total' => $validated['total'],
                'transportadora_id' => $validated['transportadora_id'],
            ]);
        });

        return redirect()->route('admin.pedidos.index');
    }

    public function show(Pedido $pedido)
    {
        $pedido->load('transportadora');

        return view('admin.pedidos.show', compact('pedido'));
    }

    public function edit(Pedido $pedido)
    {
        $transportadoras = Transportadora::all();

        return view('admin.pedidos.edit', compact('pedido', 'transportadoras'));
    }

    public function update(UpdatePedidoRequest $request, Pedido $pedido)
    {
        $validated = $request->validated();
        DB::transaction(function () use ($validated, $pedido) {
            $pedido->update([
                'descricao' => $validated['descricao'],
                'cliente_nome' => $validated['nome_cliente'],
                'produto' => $validated['produto'],
                'preco' => $validated['preco'],
                'quantidade' => $validated['quantidade'],
                'total' => $validated['total'],
                'transportadora_id' => $validated['transportadora_id'],
            ]);
        });

        return redirect()->route('admin.pedidos.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.pedidos.index');
    }
}
