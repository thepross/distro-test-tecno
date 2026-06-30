<div class="modal fade" id="registrarPagoModal{{ $venta->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Registrar Pago – Venta #{{ $venta->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ $venta->plan ? route('planes.pagarCuota', $venta->id) : route('pagos.store', $venta->id) }}"  method="POST">
                @csrf
                <div class="modal-body">

                     <!-- Detalle de la venta en tabla -->
                    <h6>Detalle de la Venta:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Cantidad</th>
                                    <th>P/U</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($venta->detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->producto->nombre ?? 'Producto' }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>${{ number_format($detalle->precio, 2) }}</td>
                                        <td>${{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>${{ number_format($venta->total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- SI EXISTE PLAN DE PAGO → MOSTRAR CUOTAS --}}
                    @if ($venta->plan)

                        <h5 class="mt-3">Cuotas del plan de pago</h5>

                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th># Cuota</th>
                                    <th>Monto</th>
                                    <th>Vencimiento</th>
                                    <th>Estado</th>
                                    <th>Pagar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venta->plan->cuotas as $index => $cuota)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>${{ number_format($cuota->monto, 2) }}</td>
                                        <td>{{ $cuota->fecha_vencimiento }}</td>
                                        <td>
                                            @if($cuota->estado == 'pagado')
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-warning">Pendiente</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($cuota->estado == 'pendiente')
                                                <input type="checkbox" name="cuotas[]" value="{{ $cuota->id }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="alert alert-info">
                            Seleccione las cuotas que desea marcar como pagadas.
                        </div>

                    @else
                    {{-- SI NO HAY PLAN DE PAGO → PAGO INDEPENDIENTE --}}

                        <div class="form-group mb-3">
                            <label>Monto a pagar</label>
                            <input type="number" 
                                class="form-control" 
                                name="monto" 
                                step="0.01" 
                                min="0.01" 
                                value="{{ number_format($venta->total, 2) }}" 
                                max="{{ number_format($venta->total, 2) }}"
                                readonly>
                            <small>Total de la venta: ${{ number_format($venta->total, 2) }}</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Tipo de pago</label>
                            <select class="form-control" name="tipo_pago" required>
                                <option value="">Seleccione...</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="yape">Yape</option>
                            </select>
                        </div>

                    @endif
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        Registrar Pago
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
