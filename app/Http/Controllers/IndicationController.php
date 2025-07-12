<?php

namespace App\Http\Controllers;

use App\Models\Indication;
use Illuminate\Http\Request;

class IndicationController extends Controller
{
    public function index()
    {
        $indications = Indication::paginate(10); // Paginação com 10 indicações por página
        return view('indications.index', compact('indications'));
    }

    public function create()
    {
        return view('indications.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        Indication::create($request->only(['descricao']));

        return redirect()->route('indications.index')->with('success', 'Indicação cadastrada com sucesso!');
    }

    public function edit(Indication $indication)
    {
        return view('indications.form', compact('indication'));
    }

    public function update(Request $request, Indication $indication)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        $indication->update($request->only(['descricao']));

        return redirect()->route('indications.index')->with('success', 'Indicação atualizada com sucesso!');
    }

    public function destroy(Indication $indication)
    {
        $indication->delete();
        return redirect()->route('indications.index')->with('success', 'Indicação excluída com sucesso!');
    }
}
