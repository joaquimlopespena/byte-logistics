<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transportadora\StoreTransportadoraRequest;
use App\Http\Requests\Transportadora\UpdateTransportadoraRequest;
use App\Models\Transportadora;
use App\Service\FilterTransportadoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportadoraController extends Controller
{
    public function index(Request $request)
    {
        $transportadoras = (new FilterTransportadoraService())->filtrarTransportadoras($request->all())->paginate(10);

        return view('admin.transportadoras.index', compact('transportadoras'));
    }

    public function create()
    {
        return view('admin.transportadoras.create');
    }

    public function store(StoreTransportadoraRequest $request)
    {
        $validated = $request->validated();
        DB::transaction(function () use ($validated) {
            Transportadora::create([
                'nome' => $validated['nome'],
                'cnpj' => $validated['cnpj'],
                'cep' => $validated['cep'],
                'logradouro' => $validated['logradouro'],
                'numero' => $validated['numero'],
                'complemento' => $validated['complemento'],
                'bairro' => $validated['bairro'],
                'cidade' => $validated['cidade'],
                'uf' => $validated['uf'],
            ]);
        });

        return redirect()->route('admin.transportadoras.index')->with('success', 'Transportadora cadastrada com sucesso');
    }

    public function show(Transportadora $transportadora)
    {
        return view('admin.transportadoras.show', compact('transportadora'));
    }

    public function edit($id)
    {
        $transportadora = Transportadora::findOrFail($id);

        return view('admin.transportadoras.edit', compact('transportadora'));
    }

    public function update(UpdateTransportadoraRequest $request, Transportadora $transportadora)
    {
        $validated = $request->validated();
        DB::transaction(function () use ($validated, $transportadora) {
            $transportadora->update([
                'nome' => $validated['nome'],
                'cnpj' => $validated['cnpj'],
                'cep' => $validated['cep'],
                'logradouro' => $validated['logradouro'],
                'numero' => $validated['numero'],
                'complemento' => $validated['complemento'],
                'bairro' => $validated['bairro'],
                'cidade' => $validated['cidade'],
                'uf' => $validated['uf'],
            ]);
        });
        return redirect()->route('admin.transportadoras.index');
    }

    public function destroy(Transportadora $transportadora)
    {
        DB::transaction(function () use ($transportadora) {
            $transportadora->delete();
        });
        return redirect()->route('admin.transportadoras.index')->with('success', 'Transportadora deletada com sucesso');
    }
}
