@extends('dashboard')
@section('title', 'G. Privilegio')

@section('content')

<div class="card-header">
        <h1 class="card-title">
            <i class="fas fa-clock mr-1"></i>
            <b>GESTIONAR PRIVILEGIO   </b> 
        </h1>
        
    </div>

<div class="card mb-4">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ROL</th>
                    <th>PRIVILEGIOS</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>

            <tbody>
                @foreach($roles as $rol)
                <tr>
                    <td class="align-middle">
                        <b>{{ $rol->nombre }}</b>
                    </td>

                    <td class="p-0">

                        {{-- MINI TABLA DE PRIVILEGIOS --}}
                        <table class="table table-sm table-striped table-hover mb-0">
                            <thead>
                                <tr class="table-light">
                                    <th>Funcionalidad</th>
                                    <th>Agregar</th>
                                    <th>Borrar</th>
                                    <th>Modificar</th>
                                    <th>Leer</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($rol->privilegiosAgrupados as $p)
                                <tr>
                                    <td>{{ $p->funcionalidad }}</td>

                                    <td>{!! $p->agregar ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                                    <td>{!! $p->borrar ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                                    <td>{!! $p->modificar ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                                    <td>{!! $p->leer ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </td>

                    <td class="align-middle text-center">
                        <a class="btn btn-success" data-toggle="modal"
                        data-target="#asignarPrivilegio{{ $rol->id }}">
                            Asignar Privilegio
                        </a>
                    </td>
                </tr>
                
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@foreach($roles as $rol)
    @include('Privilegio.asignar_privilegio', ['rol' => $rol, 'funcionalidades' => $funcionalidades])
@endforeach

@endsection
