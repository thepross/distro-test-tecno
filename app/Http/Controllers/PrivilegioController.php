<?php

namespace App\Http\Controllers;

use App\Models\Privilegio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use App\Models\Role;
use Inertia\Inertia;

class PrivilegioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(14);

        $privilegios = Privilegio::with('rol')->where('state', 'a')->get();
        $funcionalidades = Privilegio::select('funcionalidad')->distinct()->pluck('funcionalidad');

        $roles = Role::with('privilegios')->where('state', 'a')->get();

        foreach ($roles as $rol) {
            $rol->privilegiosAgrupados = $rol->privilegios
                ->groupBy('funcionalidad')
                ->map(function ($items) {
                    // Fusiona permisos por funcionalidad
                    return (object) [
                        'agregar' => $items->pluck('agregar')->contains(true) || false,
                        'borrar' => $items->pluck('borrar')->contains(true) || false,
                        'modificar' => $items->pluck('modificar')->contains(true) || false,
                        'leer' => $items->pluck('leer')->contains(true) || false,
                        'id' => $items->first()->id,
                        'funcionalidad' => $items->first()->funcionalidad,
                    ];
                });
        }

        return Inertia::render('Privilegio/Index', [
            'privilegios' => $privilegios,
            'roles' => $roles,
            'num' => $num,
            'funcionalidades' => $funcionalidades,
        ]);
    }

    public function asignar(Request $request, $idRol)
    {
        $rol = Role::findOrFail($idRol);

        $data = $request->input('privilegios', []);

        // Borramos los privilegios anteriores del rol
        Privilegio::where('id_rol', $idRol)->delete();

        foreach ($data as $funcionalidad => $permisos) {

            Privilegio::create([
                'id_rol' => $idRol,
                'funcionalidad' => $funcionalidad,
                'agregar' => isset($permisos['agregar']),
                'borrar' => isset($permisos['borrar']),
                'modificar' => isset($permisos['modificar']),
                'leer' => isset($permisos['leer']),
                'state' => 'a'
            ]);
        }

        return to_route('privilegio.index')->with('success', 'Privilegios actualizados correctamente');
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
    $request->validate([
        'rol_id' => 'required|exists:roles,id',
        'funcion' => 'required|string',
    ]);

    // Evitar duplicados
    $exists = Privilegio::where('id_rol', $request->rol_id)
        ->where('funcionalidad', $request->funcion)
        ->first();

    if ($exists) {
        return back()->withErrors([
            'funcion' => 'Este privilegio ya está asignado a este rol.'
        ]);
    }

    Privilegio::create([
        'id_rol'        => $request->rol_id,
        'funcionalidad' => $request->funcion,
        'agregar'       => $request->boolean('agregar'),
        'borrar'        => $request->boolean('borrar'),
        'modificar'     => $request->boolean('modificar'),
        'leer'          => $request->boolean('leer'),
        'state'         => 'a',
    ]);

    return back()->with('success', 'Privilegio creado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(Privilegio $privilegio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Privilegio $privilegio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Role $rol)
{
    // Validamos formato
    $request->validate([
        'privilegios' => 'required|array',
        'privilegios.*.id' => 'required|exists:privilegios,id',
        'privilegios.*.agregar' => 'boolean',
        'privilegios.*.borrar' => 'boolean',
        'privilegios.*.modificar' => 'boolean',
        'privilegios.*.leer' => 'boolean',
        'privilegios.*.funcionalidad' => 'string'
    ]);

    foreach ($request->privilegios as $data) {
        $priv = Privilegio::find($data['id']);

        // IMPORTANTE: convertir a booleanos reales (porque Vue manda true/false como boolean)
        $priv->agregar   = (bool) $data['agregar'];
        $priv->borrar    = (bool) $data['borrar'];
        $priv->modificar = (bool) $data['modificar'];
        $priv->leer      = (bool) $data['leer'];

        $priv->save();
    }

    return back()->with('success', 'Privilegios actualizados correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Privilegio $privilegio)
    {
        $privilegio->state = 'i';
        $privilegio->save();
        return to_route('privilegio.index')->with('success', 'Privilegio desactivado exitosamente.');
    }
}
