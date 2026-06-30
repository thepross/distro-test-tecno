@extends('dashboard')

@section('title', 'G. Producto')

@section('content')
<div class="card-header">
                <h1 class="card-title">
                <i class="fas fa-clock mr-1"></i>
                <b>GESTIONAR PRODUCTO</b> 
                </h1>
                @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Producto']) && $v['state'] === 'a' && $v['agregar'] ;}, ARRAY_FILTER_USE_BOTH))
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
        <table class="table table-hover" id="productos">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>DESCRIPCION</th>
                    <th>PRECIO</th>
                    <th>CATEGORIA</th>
                    <th>FECHA INGRESO</th>
                    <th>PROMOCION</th>
                    <th>STOCK</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>
                        @if($producto->promocion)
                            @if($producto->precio != $producto->precioConDescuento())
                                <span style="text-decoration: line-through; color: red;">
                                    ${{ number_format($producto->precio, 2) }}
                                </span>
                                <span style="color: green;">
                                    ${{ number_format($producto->precioConDescuento(), 2) }}
                                </span>
                            @else
                                ${{ number_format($producto->precio, 2) }}
                            @endif
                        @else
                            ${{ number_format($producto->precio, 2) }}
                        @endif
                    </td>
                    <td>{{ $producto->categoria->nombre ?? 'N/A'}}</td>
                    <td>{{ $producto->fecha_ingreso }}</td>
                    <td>{{ $producto->promocion->nombre ?? 'N/A'}}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>
                        @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Producto']) && $v['state'] === 'a' && $v['modificar'] ;}, ARRAY_FILTER_USE_BOTH))
                        <a href="#" data-toggle="modal" data-target="#editModal{{ $producto->id }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
                        &nbsp;
                        @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Producto']) && $v['state'] === 'a' && $v['borrar'] ;}, ARRAY_FILTER_USE_BOTH))
                        <a href="#" data-toggle="modal" data-target="#deleteModal{{ $producto->id }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                        @endif
                    </td>
                </tr>
                @include('Producto.modificar', ['producto' => $producto])
                @include('Producto.eliminar', ['producto' => $producto])  
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('Producto.agregar') 
@endsection