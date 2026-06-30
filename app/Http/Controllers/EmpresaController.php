<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $num = (new Contador())->contarModel(15);
        $datos = Empresa::find(1);

        return Inertia::render('Configuracion/Index', [
            'datos' => $datos,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //
    }

    public function intruso()
    {
        return view('intruso');
    }

    public function nombre(Request $request, Empresa $empresa)
    {
        $data = $request->validate(['nombre' => 'required|string|max:255']);
        $empresa->update(['nombre' => $data['nombre']]);

        return to_route('empresa.index')->with('success', 'Nombre actualizado exitosamente.');
    }

    public function direccion(Request $request, Empresa $empresa)
    {
        $data = $request->validate(['direccion' => 'required|string|max:255']);
        $empresa->update(['direccion' => $data['direccion']]);

        return to_route('empresa.index')->with('success', 'Dirección actualizada exitosamente.');
    }

    public function correo(Request $request, Empresa $empresa)
    {
        $data = $request->validate(['correo' => 'required|email|max:255']);
        $empresa->update(['correo' => $data['correo']]);

        return to_route('empresa.index')->with('success', 'Correo electrónico actualizado exitosamente.');
    }

    public function telefono(Request $request, Empresa $empresa)
    {
        $data = $request->validate(['telefono' => 'required|string|max:50']);
        $empresa->update(['telefono' => $data['telefono']]);

        return to_route('empresa.index')->with('success', 'Teléfono actualizado exitosamente.');
    }
}
