<?php

namespace App\Http\Controllers;

use App\Models\Surgery_type;
use Illuminate\Http\Request;

class SurgeryTypeController extends Controller
{
    public function index() 
{
    // Ordena pela coluna 'created_at' em ordem decrescente para exibir os mais recentes primeiro.
    $surgeryTypes = Surgery_type::orderBy('created_at', 'desc')->paginate(10); // Exibe 10 registros por página
    
    return view('surgery_types.index', compact('surgeryTypes'));
}

    public function create()
    {
        return view('surgery_types.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'tipo' => 'required|in:Obstetrica,Ginecologica,Pediatrica,Ortopedica,Geral',
        ]);

        Surgery_type::create($request->only(['descricao', 'tipo']));

        return redirect()->route('surgery_types.index')->with('success', 'Tipo de cirurgia cadastrado com sucesso!');
    }

    public function edit(Surgery_type $surgeryType)
    {
        return view('surgery_types.form', compact('surgeryType'));
    }

    public function update(Request $request, Surgery_type $surgeryType)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'tipo' => 'required|in:Obstetrica,Ginecologica,Pediatrica',
        ]);

        $surgeryType->update($request->only(['descricao', 'tipo']));

        return redirect()->route('surgery_types.index')->with('success', 'Tipo de cirurgia atualizado com sucesso!');
    }

    public function destroy(Surgery_type $surgeryType)
    {
        $surgeryType->delete();
        return redirect()->route('surgery_types.index')->with('success', 'Tipo de cirurgia excluído com sucesso!');
    }
}
