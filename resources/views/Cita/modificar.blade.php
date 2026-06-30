<div class="modal fade" id="editModal{{ $cita->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $cita->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel{{ $cita->id }}">Editar Cita</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('cita.update', $cita->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
              <label for="fecha_hora" class="form-label">Fecha y Hora</label>
              <input type="datetime-local" class="form-control" name="fecha_hora" value="{{ $cita->fecha_hora }}" required>
          </div>

          <div class="mb-3">
              <label for="estado" class="form-label">Estado</label>
              <select class="form-control" name="estado" required>
                  <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                  <option value="completada" {{ $cita->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                  <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
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
