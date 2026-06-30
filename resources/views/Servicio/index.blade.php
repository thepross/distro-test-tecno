@extends('dashboard')

@section('title', 'G. Servicio')

@section('content')
<div class="card-header">
                <h1 class="card-title">
                <i class="fas fa-clock mr-1"></i>
                <b>GESTIONAR SERVICIO</b> 
                </h1>
                @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Servicio']) && $v['state'] === 'a' && $v['agregar'] ;}, ARRAY_FILTER_USE_BOTH))
                <div class="float-right d-sm-block"> 
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <a href="#" data-toggle="modal" data-target="#agregarModal" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Agregar</a>
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
            <table class="table table-hover" id="servicios">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>DESCRIPCION</th>
                        <th>DURACION</th>
                        <th>PRECIO</th>
                        <th>PROMOCION</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->id }}</td>
                        <td>{{ $servicio->nombre }}</td>
                        <td>{{ $servicio->descripcion }}</td>
                        <td>{{ $servicio->duracion }}</td>
                        <td>
                            @if($servicio->promocion) 
                                @if($servicio->precio != $servicio->precioConDescuento())
                                    <span style="text-decoration: line-through; color: red;">${{ $servicio->precio }}</span>
                                    <span style="color: green;">${{ $servicio->precioConDescuento() }}</span>
                                @else
                                    ${{ $servicio->precio }}
                                @endif
                            @else
                                ${{ $servicio->precio }}
                            @endif
                        </td>
                        <td>{{ $servicio->promocion->nombre ?? 'Sin promo' }}</td>
                        <td>
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Servicio']) && $v['state'] === 'a' && $v['modificar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#editModal{{ $servicio->id }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            @endif
                            &nbsp;
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Servicio']) && $v['state'] === 'a' && $v['borrar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#deleteModal{{ $servicio->id }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                            @endif
                        </td>
                    </tr>
                    @include('Servicio.modificar', ['servicio' => $servicio])
                    @include('Servicio.eliminar', ['servicio' => $servicio])                
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        @include('Servicio.agregar')             
@endsection