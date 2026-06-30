@extends('dashboard')

@section('title', 'Dashboard')

@section('content')
    @if (auth()->user()->rol->id === 1)
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $cantidadVentas ?? 0 }}</h3>
                                <p>Nro Ventas</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $cantidadVendida ?? 0 }}<sup style="font-size: 20px">Bs</sup></h3>
                                <p>Ingresos por ventas</p>
                            </div>
                            <div class="icon"><i class="ion ion-stats-bars"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $cantidadClientes ?? 0 }}</h3>
                                <p>Clientes registrados</p>
                            </div>
                            <div class="icon"><i class="ion ion-person-add"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $cantidadVisitas ?? 0 }}</h3>
                                <p>Visitas a la página</p>
                            </div>
                            <div class="icon"><i class="ion ion-pie-graph"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="row">
                    <section class="col-lg-6 connectedSortable">
                        <div class="card bg-gradient">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h3>VENTAS POR MES</h3>

                                @if (!empty($mes) && !empty($cantidad))
                                    <canvas id="graficoTorta"></canvas>
                                @else
                                    <p class="text-muted">No hay datos de ventas por mes.</p>
                                @endif
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h3>PRODUCTOS TOP</h3>
                            </div>

                            @if (!empty($productosTop) && count($productosTop) > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Unidades</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        @foreach ($productosTop as $producto)
                                            <tr>
                                                <td>{{ $producto->producto }}</td>
                                                <td>{{ $producto->total_vendido }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center text-muted pb-3">No hay productos registrados.</p>
                            @endif
                        </div>
                    </section>

                    <section class="col-lg-6 connectedSortable">
                        <div class="card bg-gradient">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h3>VENTAS POR DÍA</h3>

                                @if (!empty($dias) && !empty($cantidadDias))
                                    <canvas id="graficoTorta2"></canvas>
                                @else
                                    <p class="text-muted">No hay datos de ventas por día.</p>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>

        <!-- Scripts de gráficos -->
        @if (!empty($mes) && !empty($cantidad))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('graficoTorta').getContext('2d');
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: @json($mes),
                            datasets: [{
                                data: @json($cantidad),
                                backgroundColor: [
                                    'rgba(75, 192, 192, 0.5)',
                                    'rgba(255, 99, 132, 0.5)',
                                ],
                                borderWidth: 1,
                            }]
                        },
                    });
                });
            </script>
        @endif

        @if (!empty($dias) && !empty($cantidadDias))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('graficoTorta2').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($dias),
                            datasets: [{
                                label: 'Ventas por Día',
                                data: @json($cantidadDias),
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endif

    @endif
@endsection
