<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::paginate(10);
        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        return view('admin.pedidos.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.pedidos.index');
    }

    public function show($id)
    {
        return view('admin.pedidos.show');
    }

    public function edit($id)
    {
        return view('admin.pedidos.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.pedidos.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.pedidos.index');
    }
}
