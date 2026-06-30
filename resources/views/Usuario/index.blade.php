@extends('dashboard')

@section('title', 'Usuario')

@section('content')

<div class="card-header">
    <h3 class="card-title">
    <i class="fas fa-user mr-1"></i>
    GESTIONAR USUARIO    
    </h3>
    @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Usuario']) && $v['state'] === 'a' && $v['agregar'] ;}, ARRAY_FILTER_USE_BOTH))
    <div class="float-right d-sm-block"> 
        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="#" data-toggle="modal" data-target="#addUserModal" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Agregar</a>
        </div> 
    </div>
    @endif
     
</div><!-- /.card-header -->


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
            <table class="table table-hover" id="usuarios">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>EMAIL</th>
                        <th>ROL</th>
                        <th>TELEFONO</th>
                        <th>ACCIONES</th>                        
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>  
                        <td>{{ $usuario->nombre }}</td>  
                        <td>{{ $usuario->email }}</td>  
                        <td>{{ $usuario->rol->nombre }}</td>  
                        <td>{{ $usuario->telefono }}</td>
                        <td>
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Usuario']) && $v['state'] === 'a' && $v['modificar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#editModal{{ $usuario->id }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            @endif
                            &nbsp;
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Usuario']) && $v['state'] === 'a' && $v['borrar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#deleteModal{{ $usuario->id }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                            @endif
                        </td>  
                    </tr>
                    @include('Usuario.modificar', ['usuario' => $usuario])
                    @include('Usuario.eliminar', ['usuario' => $usuario]) 
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('Usuario.agregar')
@endsection