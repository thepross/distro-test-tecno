<div class="modal fade" id="planPagoModal" tabindex="-1" aria-labelledby="planPagoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="planPagoModalLabel">Crear Plan de Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('planes.store', ':ventaId') }}" id="formPlanPago" method="POST">
                @csrf
                <div class="modal-body">
                    <p><b>Total venta:</b> $<span id="totalVentaTexto"></span></p>
                    <hr>

                    <!-- Campos ocultos -->
                    <input type="hidden" name="id_venta" id="id_venta">
                    <input type="hidden" name="total_deuda" id="total_deuda">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Cantidad de cuotas</label>
                            <input type="number" class="form-control" id="cantidad_cuotas" name="cantidad_cuotas" min="1" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Total deuda</label>
                            <input type="number" step="0.01" class="form-control" id="total_deuda_visible" name="total_deuda" readonly>
                        </div>

                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="generar_cuotas">Generar cuotas</button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <table class="table table-bordered" id="tabla_cuotas">
                            <thead>
                                <tr>
                                    <th># Cuota</th>
                                    <th>Monto</th>
                                    <th>Fecha vencimiento</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label>Fecha inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Estado</label>
                            <select class="form-control" name="estado" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="en_curso">En curso</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Crear Plan de Pago</button>
                </div>

            </form>

        </div>
    </div>
</div>
