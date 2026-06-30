@extends('dashboard')

@section('title', 'Resultado')

@section('content')
    <div class="card-header">
        <h1 class="card-title">
        <i class="fas fa-search mr-1"></i>
        <b>RESULTADO DE LA BUSQUEDA</b> 
        </h1>
    </div>
    
    <div class="card table-responsive">
        <div class="card-body">

            <table class="table table-hover" id="menus">
                <thead>
                    <tr>
                        <th>RUTA</th>
                        <th>COINCIDENCIAS</th>
                        <th>TABLA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td> <a href="{{ $d->ruta }}">Ruta</a> </td>
                            <td>{{ $d->nombre }}</td>
                            <td>{{ $d->modelo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        
@endsection
