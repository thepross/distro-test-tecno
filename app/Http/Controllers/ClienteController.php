<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use App\Models\Usuario;
use Inertia\Inertia;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(2);

        $clientes = Cliente::with(['user'])->where('state', 'a')->get();

        // return view('Cliente.index', compact('clientes', 'num'));
        return Inertia::render('Cliente/Index', [
            'clientes' => $clientes,
            'num' => $num,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required|digits:8|max:8',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Crear el usuario
        $user = Usuario::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'estado' => 'a',
            'id_empresa' => 1,
            'id_rol' => 4,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
        ]);

        // Crear el cliente
        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'id_user' => $user->id,
        ]);

        return to_route('cliente.index')->with('success', 'Cliente creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required|digits:8|max:8',
            'email' => 'required|email|unique:users,email,' . $cliente->user->id,
        ]);

        //dd($cliente);
        // Actualizar el usuario
        $cliente->user->update([
            'email' => $request->email,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
        ]);

        // Si se proporciona una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $cliente->user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        // Actualizar el cliente
        $cliente->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
        ]);

        return to_route('cliente.index')->with('success', 'Cliente actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->update(['state' => 'i']);

        // Cambiar el estado del usuario asociado a 'i'
        $cliente->user->update(['state' => 'i']);

        return to_route('cliente.index')->with('success', 'Cliente desactivado exitosamente');
    }
}
