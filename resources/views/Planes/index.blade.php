@extends('dashboard')

@section('title', 'G. Plan de Pago')

@section('content')
<div class="card-header">
                <h1 class="card-title">
                <i class="fas fa-clock mr-1"></i>
                <b>GESTIONAR PLAN DE PAGO   </b> 
                </h1>
                <div class="float-right d-sm-block"> 
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <a href="#" data-toggle="modal" data-target="#agregarModal" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Agregar</a>
                    </div> 
                </div>
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
        <table class="table table-hover" id="promociones">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>CANT. CUOTAS</th>
                    <th>FECHA INICIO</th>
                    <th>MONTO CUOTA</th>
                    <th>TOTAL DEUDA</th>
                    <th>SALDO RESTANTE</th>
                    <th>ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach($planes as $plan)
                <tr>
                    <td>{{ $plan->id }}</td>
                    <td>{{ $plan->cantidad_cuotas }}</td>
                    <td>{{ $plan->fecha_inicio }}</td>
                    <td>{{ $plan->monto_cuota }}</td>
                    <td>{{ $plan->total_deuda}}</td>
                    <td>{{ $plan->saldo_restante}}</td>
                    <td>{{ $plan->estado}}</td>
                    <td>
                        <a href="#" data-toggle="modal" data-target="#editModal{{ $plan->id }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        &nbsp;
                        <a href="#" data-toggle="modal" data-target="#deleteModal{{ $plan->id }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                </tr>
                @include('Planes.modificar', ['plan' => $plan])
                @include('Planes.eliminar', ['plan' => $plan])                
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('Planes.agregar')             
@endsection