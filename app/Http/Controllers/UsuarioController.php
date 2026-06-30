<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    public function index()
    {
        $num = (new Contador())->contarModel(2);
        return Inertia::render('Usuario/Index', [
            'usuarios' => Usuario::with('rol')->where('state', 'a')->orderBy('id')->get(),
            'roles' => Role::where('state', 'a')->orderBy('nombre')->get(),
            'num' => $num,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'ci' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'direccion' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username',
            'password' => 'required|string|min:4',
            'id_rol' => 'required|exists:roles,id',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $data['username'] ?: explode('@', $data['email'])[0];
        $data['state'] = 'a';
        Usuario::create($data);
        return to_route('usuario.index')->with('success', 'Usuario agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'ci' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($usuario->id)],
            'direccion' => 'nullable|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($usuario->id)],
            'password' => 'nullable|string|min:4',
            'id_rol' => 'required|exists:roles,id',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $usuario->update($data);
        return to_route('usuario.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Usuario::findOrFail($id)->update(['state' => 'i']);
        return to_route('usuario.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
