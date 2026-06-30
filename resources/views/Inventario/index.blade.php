@extends('dashboard')

@section('title', 'G. Inventario')

@section('content')
<div class="card-header">
                <h1 class="card-title">
                <i class="fas fa-clock mr-1"></i>
                <b>GESTIONAR INVENTARIO   </b> 
                </h1>
                @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Inventario']) && $v['state'] === 'a' && $v['agregar'] ;}, ARRAY_FILTER_USE_BOTH))
                <div class="float-right d-sm-block"> 
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <a href="#" data-toggle="modal" data-target="#addModal" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Agregar</a>
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
            <table class="table table-hover" id="clientes">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>DESCRIPCION</th>
                        <th>CANTIDAD</th>
                        <th>FECHA</th>
                        <th>PRODUCTO</th>
                        <th>TIPO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                   @foreach($inventarios as $inventario)
                    <tr>
                        <td>{{$inventario->id}}</td>
                        <td>{{$inventario->descripcion}}</td>
                        <td>{{$inventario->cantidad}}</td>
                        <td>{{$inventario->fecha}}</td>
                        <td>{{$inventario->producto->nombre}}</td>
                        <td>{{$inventario->tipo}}</td>
                        <td>
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Inventario']) && $v['state'] === 'a' && $v['modificar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#editModal{{ $inventario->id }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            @endif
                            &nbsp;
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Inventario']) && $v['state'] === 'a' && $v['borrar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#deleteModal{{ $inventario->id }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                            @endif
                        </td>
                    </tr>
                    @include('Inventario.modificar', ['inventario' => $inventario])
                    @include('Inventario.eliminar', ['inventario' => $inventario]) 
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('Inventario.agregar')   
@endsection