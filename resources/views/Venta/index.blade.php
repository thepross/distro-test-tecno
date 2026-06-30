@extends('dashboard')

@section('title', 'G. Venta')

@section('content')
<div class="card-header">
    <h1 class="card-title">
        <i class="fas fa-clock mr-1"></i>
        <b>GESTIONAR VENTA</b> 
    </h1>
    @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Venta']) && $v['state'] === 'a' && $v['agregar'] ;}, ARRAY_FILTER_USE_BOTH))
    <div class="float-right d-sm-block"> 
        <div class="btn-group">
            <a href="" data-toggle="modal" data-target="#agregarModal" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Agregar</a>
        </div> 
    </div>
    @endif
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="card table-responsive">
    <div class="card-body">
        <table class="table table-hover" id="ventas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CLIENTE</th>
                    <th>REGISTRADO POR</th>
                    <th>FECHA</th>
                    <th>TOTAL</th>
                    <th>ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->cliente->nombre ?? 'No asignado' }}</td>
                        <td>{{ $venta->usuario->nombre ?? 'No asignado' }}</td>
                        <td>{{ $venta->fecha_venta }}</td>
                        <td>${{ number_format($venta->total, 2) }}</td>
                        <td>{{ $venta->estado }}</td>
                        <td>
                            @php
                                $totalPagado = $venta->pagos->sum('monto');
                                $totalCuotasPagadas = $venta->plan && $venta->plan->cuotas ? $venta->plan->cuotas->where('estado', 'pagado')->sum('monto') : 0;
                                $totalAbonado = $totalPagado + $totalCuotasPagadas;
                                $ventaPagada = $totalAbonado >= $venta->total;
                            @endphp
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalleVentaModal" data-venta-id="{{ $venta->id }}">
                                Ver Detalles
                            </button>
                            &nbsp;
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Pago']) && $v['state'] === 'a' && $v['agregar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#registrarPagoModal{{ $venta->id }}" @if($ventaPagada) disabled title="Pago completo" @endif>
                                Registrar Pago
                            </button>
                            &nbsp;
                            <button 
                                class="btn btn-warning btn-sm btnPlanPago" 
                                data-bs-toggle="modal" 
                                data-bs-target="#planPagoModal"
                                data-venta="{{ $venta->id }}"
                                data-total="{{ $venta->total }}"
                                {{ $venta->plan || $venta->pagos->sum('monto') >= $venta->total ? 'disabled' : '' }}>
                                Plan de pago
                            </button>
                            @endif
                            &nbsp;
                            @if(array_filter(auth()->user()->rol->privilegios->toArray(), function($v, $k) {return in_array($v['funcionalidad'], ['Venta']) && $v['state'] === 'a' && $v['borrar'] ;}, ARRAY_FILTER_USE_BOTH))
                            <a href="#" data-toggle="modal" data-target="#deleteModal{{ $venta->id }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal de detalles -->
        <div class="modal fade" id="detalleVentaModal" tabindex="-1" aria-labelledby="detalleVentaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleVentaModalLabel">Detalles de Venta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="detalleVentaModalBody">
                        <!-- Aquí se cargará el HTML de detalles vía AJAX -->
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>

@foreach($ventas as $venta)
    @include('Venta.registrar_pago', ['venta' => $venta])
    @include('Venta.eliminar', ['venta' => $venta])
@endforeach

<script>
    // Cargar detalles de venta en el modal
    var detalleVentaModal = document.getElementById('detalleVentaModal');
    detalleVentaModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var ventaId = button.getAttribute('data-venta-id');
        var detallesUrl = "{{ route('ventas.detalles', ':id') }}".replace(':id', ventaId);

        fetch(detallesUrl)
            .then(response => response.text())
            .then(html => {
                document.getElementById('detalleVentaModalBody').innerHTML = html;
            });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const planPagoButtons = document.querySelectorAll('.btnPlanPago');
    planPagoButtons.forEach(button => {
        button.addEventListener('click', function () {
            const ventaId = this.getAttribute('data-venta');
            const totalVenta = parseFloat(this.getAttribute('data-total'));

            // Rellenar valores del modal
            document.getElementById('id_venta').value = ventaId;
            document.getElementById('total_deuda').value = totalVenta;
            document.getElementById('total_deuda_visible').value = totalVenta;
            document.getElementById('totalVentaTexto').textContent = totalVenta;

            // REEMPLAZAR LA RUTA DEL FORMULARIO CON EL ID DE LA VENTA
            let form = document.getElementById('formPlanPago');
            form.action = "{{ route('planes.store', ':ventaId') }}".replace(':ventaId', ventaId);

            // Limpiar tabla de cuotas
            document.querySelector('#tabla_cuotas tbody').innerHTML = '';
        });
    });

    // Generar las cuotas dinámicamente cuando se presiona el botón 'Generar cuotas'
    document.querySelectorAll('#generar_cuotas').forEach(button => {
        button.addEventListener('click', function () {
            const ventaId = document.getElementById('id_venta').value;
            const totalVenta = parseFloat(document.getElementById('total_deuda_visible').value);
            const numCuotas = document.getElementById('cantidad_cuotas').value;
            const tbody = document.querySelector('#tabla_cuotas tbody');

            tbody.innerHTML = ''; // Limpiar tabla

            if (numCuotas <= 0) {
                alert('Debe ingresar un número válido de cuotas.');
                return;
            }

            // Generar las filas de cuotas
            for (let i = 1; i <= numCuotas; i++) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${i}</td>
                    <td><input type="number" name="cuotas[${i}][monto]" class="form-control cuotaMonto" step="0.01" required></td>
                    <td><input type="date" name="cuotas[${i}][fecha]" class="form-control" required></td>
                `;
                tbody.appendChild(row);
            }
        });
    });

    // Validar antes de enviar el formulario
    document.getElementById('formPlanPago').addEventListener('submit', function (event) {
        let totalCuotas = 0;
        const cuotas = document.querySelectorAll('.cuotaMonto');
        
        // Sumar los montos de las cuotas
        cuotas.forEach(input => {
            totalCuotas += parseFloat(input.value) || 0; // Agregar el valor o 0 si está vacío
        });

        const totalVenta = parseFloat(document.getElementById('total_deuda_visible').value);

        // Validar que la suma de las cuotas no exceda el total de la venta
        if (totalCuotas > totalVenta) {
            event.preventDefault();  // Prevenir el envío
            alert('El total de las cuotas no puede exceder el total de la venta.');
        }
    });
});

</script>

    @include('Venta.plan_pago', ['venta' => $venta])
 @include('Venta.agregar')
@endsection
