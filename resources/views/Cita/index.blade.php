@extends('dashboard')

@section('title', 'G. Citas')

@section('content')
<div class="card-header">
    <h1 class="card-title">
        <i class="fas fa-calendar-check mr-1"></i>
        <b>GESTIONAR CITAS</b>
    </h1>
    @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Cita']) && $v['state'] === 'a' && $v['agregar'] ;}, ARRAY_FILTER_USE_BOTH))
    <div class="float-right d-sm-block">
        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="#" data-toggle="modal" data-target="#agregarModal" class="btn btn-success">
                <i class="fa fa-plus"></i>&nbsp; Agregar
            </a>
        </div>
    </div>
    @endif
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card table-responsive">
    <div class="card-body">
        <table class="table table-hover" id="citas">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Medico</th>
                    <th>Secretaria</th>
                    <th>Servicio</th>
                    <th>Fecha y Hora</th>
                    <th>Estado</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach($citas as $cita)
                <tr>
                    <td>{{ $cita->id }}</td>
                    <td>{{ $cita->cliente->nombre }}</td>
                    <td>{{ $cita->medico->nombre }}</td>
                    <td>{{ $cita->secretaria->nombre }}</td>
                    <td>{{ $cita->servicio->nombre }}</td>
                    <td>{{ $cita->fecha_hora }}</td>
                    <td>{{ $cita->estado  }}</td>
                    <td>
                        @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Cita']) && $v['state'] === 'a' && $v['modificar'] ;}, ARRAY_FILTER_USE_BOTH))
                        <a href="#" data-toggle="modal" data-target="#editModal{{ $cita->id }}"><i class="fa fa-edit"></i></a>
                        @endif
                        &nbsp;
                        @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Cita']) && $v['state'] === 'a' && $v['borrar'] ;}, ARRAY_FILTER_USE_BOTH))
                        <a href="#" data-toggle="modal" data-target="#deleteModal{{ $cita->id }}"><i class="fa fa-trash"></i></a>
                        @endif
                    </td>
                </tr>
                @include('Cita.modificar', ['cita' => $cita])
                @include('Cita.eliminar', ['cita' => $cita])
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('Cita.agregar')
@endsection
