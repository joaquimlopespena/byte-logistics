<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransportadoraController extends Controller
{
    public function index()
    {
        return view('admin.transportadoras.index');
    }

    public function create()
    {
        return view('admin.transportadoras.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.transportadoras.index');
    }

    public function show($id)
    {
        return view('admin.transportadoras.show');
    }

    public function edit($id)
    {
        return view('admin.transportadoras.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.transportadoras.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.transportadoras.index');
    }
}
