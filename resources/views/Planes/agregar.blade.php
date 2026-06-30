<!-- ... Código Agregar plan ... -->
<div class="modal fade" id="agregarModal" tabindex="-1" aria-labelledby="agregarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarModalLabel">Agregar Plan</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('planes.store') }}" method="POST">
          @csrf
          <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="mb-3">
                <label for="cantidad_cuota" class="form-label">Cantidad Cuotas </label>
                <input type="number" class="form-control" id="cantidad_cuota" name="cantidad_cuota" required>
            </div>
            <div class="mb-3">
                <label for="monto_cuota" class="form-label">Monto cuota</label>
                <input type="number" step="0.01" class="form-control" id="monto_cuota" name="monto_cuota" required>
            </div>
            <div class="mb-3">
                <label for="total_deuda" class="form-label">Total deuda</label>
                <input type="number" step="0.01" class="form-control" id="total_deuda" name="total_deuda" required>
            </div>
            <div class="mb-3">
                <label for="saldo_restante" class="form-label">Saldo restante</label>
                <input type="number" step="0.01" class="form-control" id="saldo_restante" name="saldo_restante" required>
            </div>
            <div class="mb-3">
              <label for="estado" class="form-label">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                  <option value="">Seleccionar estado</option>
                  <option value="pendiente">Pendiente</option>
                  <option value="en_curso">En curso</option>
                  <option value="finalizado">Finalizado</option>
                </select>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- ... end agregar promocion ... -->