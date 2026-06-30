@extends('dashboard')

@section('title', 'Configuracion')

@section('content')
<div class="card-header">
    <h1 class="card-title">
    <i class="fas fa-cog mr-1"></i>
    <b>CONFIGURACIÓN   </b> 
    </h1>
</div>

<div class="container mt-3">
    
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="nombre" class="form-label text-darkgray">NOMBRE DE LA EMPRESA</label>
                    <div class="input-group">
                        <label class="form-control-plaintext" id="nombre" style="font-weight: normal">{{ $datos->nombre }}</label>
                        <button class="btn btn-link" type="button">
                        <a href="#" data-toggle="modal" data-target="#editNombreModal{{ $datos->id }}"><i class="fa fa-pen" aria-hidden="true"></i></a>
                        </button>
                    </div>
                </div>

                <hr class="">
                
                <div class="mb-3">
                    <label for="nombre" class="form-label text-darkgray" style="font-size=11px">DIRECCIÓN</label>
                    <div class="input-group">
                        <label class="form-control-plaintext" id="nombre" style="font-weight: normal">{{ $datos->direccion }}</label>
                        <button class="btn btn-link" type="button">
                        <a href="#" data-toggle="modal" data-target="#editDireccionModal{{ $datos->id }}"><i class="fa fa-pen" aria-hidden="true"></i></a>
                        </button>
                    </div>
                </div>

                <hr class="">

                <div class="mb-3">
                    <label for="nombre" class="form-label text-gdarkgrayray" style="font-size=11px">CORREO ELECTRONICO</label>
                    <div class="input-group">
                        <label class="form-control-plaintext" id="nombre" style="font-weight: normal">{{ $datos->correo }}</label>
                        <button class="btn btn-link" type="button">
                        <a href="#" data-toggle="modal" data-target="#editCorreoModal{{ $datos->id }}"><i class="fa fa-pen" aria-hidden="true"></i></a>
                        </button>
                    </div>
                </div>
                
                <hr class="">

                <div class="mb-3">
                    <label for="nombre" class="form-label text-darkgray" style="font-size=11px">TELÉFONO</label>
                    <div class="input-group">
                        <label class="form-control-plaintext" id="nombre" style="font-weight: normal">{{ $datos->telefono }}</label>
                        <button class="btn btn-link" type="button">
                        <a href="#" data-toggle="modal" data-target="#editTelefonoModal{{ $datos->id }}"><i class="fa fa-pen" aria-hidden="true"></i></a>
                        </button>
                    </div>
                </div>
                
            </form>
        </div>
        @include('Configuracion.modificar', ['datos' => $datos])
</div>
@endsection