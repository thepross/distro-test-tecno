<div class="modal fade" id="agregarModal" tabindex="-1" aria-labelledby="agregarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarModalLabel">Agregar Cita</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('cita.store') }}" method="POST">
          @csrf

          <div class="mb-3">
              <label for="id_cliente" class="form-label">Cliente</label>
              <select class="form-control" name="id_cliente" required>
                  <option value="">Seleccionar Cliente</option>
                  @foreach($clientes as $cliente)
                      <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-3">
              <label for="id_medico" class="form-label">Médico</label>
              <select class="form-control" name="id_medico" required>
                  <option value="">Seleccionar Médico</option>
                  @foreach($medicos as $medico)
                      <option value="{{ $medico->id }}">{{ $medico->name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-3">
              <label for="id_secretaria" class="form-label">Secretaria</label>
              <select class="form-control" name="id_secretaria" required>
                  <option value="">Seleccionar Secretaria</option>
                  @foreach($secretarias as $secretaria)
                      <option value="{{ $secretaria->id }}">{{ $secretaria->name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-3">
              <label for="id_servicio" class="form-label">Servicio</label>
              <select class="form-control" name="id_servicio" required>
                  <option value="">Seleccionar Servicio</option>
                  @foreach($servicios as $servicio)
                      <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-3">
              <label for="fecha_hora" class="form-label">Fecha y Hora</label>
              <input type="datetime-local" class="form-control" name="fecha_hora" required>
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
