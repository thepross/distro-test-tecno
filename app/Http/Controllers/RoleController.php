<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(1);
        return Inertia::render('Rol/Index', [
            'roles' => Role::where('state', 'a')->orderBy('id')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $data['state'] = 'a';
        Role::create($data);
        return to_route('rol.index')->with('success', 'Rol agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);
        Role::findOrFail($id)->update($data);
        return to_route('rol.index')->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->update(['state' => 'i']);
        return to_route('rol.index')->with('success', 'Rol eliminado exitosamente.');
    }
}
