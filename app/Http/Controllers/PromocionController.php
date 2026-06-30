<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use Inertia\Inertia;

class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(8);

        $promociones = Promocion::where('state', 'a')->get();

        return Inertia::render('Promocion/Index', [
            'promociones' => $promociones,
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
        $request->validate([
            'descuento' => 'required|numeric|between:0,100'
        ]);

        try {
            // dd($request->all());
            $promocion = Promocion::create([
                'descuento' => $request->input('descuento'),
                'descripcion' => $request->input('descripcion'),
                'fecha_inicio' => $request->input('fecha_inicio'),
                'nombre' => $request->input('nombre'),
                'fecha_fin' => $request->input('fecha_fin'),
                'state' => 'a',
            ]);

            $promocion->save();

            Session::flash('success', 'Promocion agregado exitosamente.');
        } catch (\Exception $e) {
            Session::flash('error', 'Ocurrió un error al guardar Promocion.');
        }

        return to_route('promocion.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promocion $promocion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promocion $promocion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        /*$request->validate([
            'name' => 'required|string|max:255',
            'telefono' => 'required|integer',
        ]);*/

        $promocion = Promocion::findOrFail($id);
        $promocion->update($request->all());

        return to_route('promocion.index')->with('success', 'Promocion actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promocion = Promocion::findOrFail($id);
        $promocion->update(['state' => 'i']);

        return to_route('promocion.index')->with('success', 'Promocion eliminada exitosamente.');
    }
}
