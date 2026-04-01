<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        return view('admin.pedidos.index');
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
