<!-- editar-plan -->
<div class="modal fade" id="editModal{{ $plan->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $plan->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $plan->id }}">Editar Plan de Pago</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('planes.update', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Fecha de inicio -->
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $plan->fecha_inicio }}" required>
                    </div>

                    <!-- Cantidad de cuotas -->
                    <div class="mb-3">
                        <label for="cantidad_cuota" class="form-label">Cantidad Cuotas</label>
                        <input type="number" class="form-control" id="cantidad_cuota" name="cantidad_cuota" value="{{ $plan->cantidad_cuotas }}" required>
                    </div>

                    <!-- Monto por cuota -->
                    <div class="mb-3">
                        <label for="monto_cuota" class="form-label">Monto cuota</label>
                        <input type="number" step="0.01" class="form-control" id="monto_cuota" name="monto_cuota" value="{{ $plan->monto_cuota }}" required>
                    </div>

                    <!-- Total deuda -->
                    <div class="mb-3">
                        <label for="total_deuda" class="form-label">Total deuda</label>
                        <input type="number" step="0.01" class="form-control" id="total_deuda" name="total_deuda" value="{{ $plan->total_deuda }}" required>
                    </div>

                    <!-- Saldo restante -->
                    <div class="mb-3">
                        <label for="saldo_restante" class="form-label">Saldo restante</label>
                        <input type="number" step="0.01" class="form-control" id="saldo_restante" name="saldo_restante" value="{{ $plan->saldo_restante }}" required>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                        <option value="pendiente" {{ $plan->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_curso" {{ $plan->estado == 'en_curso' ? 'selected' : '' }}>En curso</option>
                        <option value="finalizado" {{ $plan->estado == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ... end editar plan ... -->
