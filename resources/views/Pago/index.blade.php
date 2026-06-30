@extends('dashboard')

@section('title', 'G. Pago')

@section('content')
    <div class="card-header">
        <h1 class="card-title">
            <i class="fas fa-clock mr-1"></i>
            <b>REGISTRO DE PAGOS</b>
        </h1>
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
            <table class="table table-hover" id="pagos">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>CLIENTE</th>
                        <th>TOTAL</th>
                        <th>METODO PAGO</th>
                        <th>FECHA</th>
                        @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                function ($v, $k) {
                                    return in_array($v['funcionalidad'], ['Pago']) && $v['state'] === 'a' && ($v['modificar'] || $v['borrar']);
                                },
                                ARRAY_FILTER_USE_BOTH))
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($pagos as $pago)
                        <tr>
                            <td>{{ $pago->id }}</td>
                            <td>{{ $pago->venta->cliente->nombre ?? 'N/A' }}</td>
                            <td>{{ $pago->venta->total ?? 'N/A' }}</td>
                            <td>{{ $pago->tipo_pago }}</td>
                            <td>{{ $pago->fecha_pago }}</td>
                            @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                    function ($v, $k) {
                                        return in_array($v['funcionalidad'], ['Pago']) && $v['state'] === 'a' && ($v['modificar'] || $v['borrar']);
                                    },
                                    ARRAY_FILTER_USE_BOTH))
                                <td>{{ $pago->estado_pago }} </td>
                                <td>

                                    @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                            function ($v, $k) {
                                                return in_array($v['funcionalidad'], ['Pago']) && $v['state'] === 'a' && $v['borrar'];
                                            },
                                            ARRAY_FILTER_USE_BOTH))
                                        <a href="#" data-toggle="modal" data-target="#deleteModal{{ $pago->id }}">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a>
                                    @endif
                                </td>
                            @endif
                        </tr>

                        @include('Pago.eliminar', ['pago' => $pago])
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
