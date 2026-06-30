@extends('dashboard')

@section('title', 'G. Rol')

@section('content')
<div class="card-header">
                <h1 class="card-title">
                <i class="fas fa-clock mr-1"></i>
                <b>GESTIONAR ROL   </b> 
                </h1>
                @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Rol']) && $v['state'] === 'a' && $v['agregar'] ;}, ARRAY_FILTER_USE_BOTH))
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
            <table class="table table-hover" id="roles">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach($roles as $rol)
                    <tr>
                        <td>{{ $rol->id }}</td>
                        <td>{{ $rol->nombre }}</td>
                        <td>
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Rol']) && $v['state'] === 'a' && $v['modificar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#editModal{{ $rol->id }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            @endif
                            &nbsp;
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Rol']) && $v['state'] === 'a' && $v['borrar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#deleteModal{{ $rol->id }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                            @endif
                        </td>
                    </tr>
                    @include('Rol.modificar', ['rol' => $rol])
                    @include('Rol.eliminar', ['rol' => $rol])                
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        @include('Rol.agregar')             
@endsection