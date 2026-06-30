<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Servicio;
use App\Models\Contador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class CitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(3);
        $authUser = auth()->user();
        // dd($authUser);

        $rol = $authUser->rol->nombre;

        $query = Cita::with(['cliente', 'medico', 'secretaria', 'servicio'])
            ->activas()
            ->orderBy('id', 'desc');

        if ($rol === 'Medico') {
            $query->where('id_medico', $authUser->id);
        } elseif ($rol === 'Secretaria') {
            $query->where('id_secretaria', $authUser->id);
        } elseif ($rol === 'Cliente') {
            if ($authUser->cliente) {
                $query->where('id_cliente', $authUser->cliente->id);
            } else {
                $query->where('id', -1);
            }
        }

        $citas = $query->get();
        // dd($citas);
        $clientes = Cliente::where('state', 'a')->get();
        $medicos = Usuario::whereHas('rol', function ($q) {
            $q->where('nombre', 'Medico');
        })->where('state', 'a')->get();
        $secretarias = Usuario::whereHas('rol', function ($q) {
            $q->where('nombre', 'Secretaria');
        })->where('state', 'a')->get();
        $servicios = Servicio::where('state', 'a')->get();

        return Inertia::render('Cita/Index', [
            'citas' => $citas,
            'clientes' => $clientes,
            'medicos' => $medicos,
            'secretarias' => $secretarias,
            'servicios' => $servicios,
            'num' => $num,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_hora' => 'required|date',
            'id_cliente' => 'required|exists:clientes,id',
            'id_medico' => 'required|exists:users,id',
            'id_secretaria' => 'required|exists:users,id',
            'id_servicio' => 'required|exists:servicios,id',
        ]);


        try {

            $cita = Cita::create([
                'estado' => 'pendiente',
                'fecha_hora' => $request->fecha_hora,
                'id_cliente' => $request->id_cliente,
                'id_medico' => $request->id_medico,
                'id_secretaria' => $request->id_secretaria,
                'id_servicio' => $request->id_servicio,
                'state' => 'a',
            ]);

            $cita->save();

            Session::flash('success', 'Cita agregada exitosamente.');
        } catch (\Exception $e) {
            Session::flash('error', 'Ocurrió un error al guardar cita.');
        }


        return to_route('cita.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'fecha_hora' => 'required|date',
            'estado' => 'required|string',
            'id_cliente' => 'required|exists:clientes,id',
            'id_medico' => 'required|exists:users,id',
            'id_secretaria' => 'required|exists:users,id',
            'id_servicio' => 'required|exists:servicios,id',
        ]);

        $cita->update([
            'estado' => $request->estado,
            'fecha_hora' => $request->fecha_hora,
            'id_cliente' => $request->id_cliente,
            'id_medico' => $request->id_medico,
            'id_secretaria' => $request->id_secretaria,
            'id_servicio' => $request->id_servicio,
        ]);

        return to_route('cita.index')->with('success', 'Cita actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete = Cita::findOrFail($id);
        $delete->update(['state' => 'i']);

        return to_route('cita.index')->with('success', 'Cita eliminada correctamente.');
    }
}
