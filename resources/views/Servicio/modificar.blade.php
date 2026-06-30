<!-- editar servicio -->
<div class="modal fade" id="editModal{{ $servicio->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $servicio->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel{{ $servicio->id }}">Editar Servicio</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('servicio.update', $servicio->id) }}" method="POST">
          @csrf
          @method('PUT') <!-- Usamos el método PUT para actualización -->
          
          <!-- Campo para el nombre -->
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $servicio->nombre }}" required>
          </div>

          <!-- Campo para la descripción -->
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripcion</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ $servicio->descripcion }}" required>
          </div>

          <!-- Campo para la duración -->
          <div class="mb-3">
            <label for="duracion" class="form-label">Duracion</label>
            <input type="number" class="form-control" id="duracion" name="duracion" value="{{ $servicio->duracion }}" required>
          </div>

          <!-- Campo para el precio -->
          <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" value="{{ $servicio->precio }}" required>
          </div>

          <!-- Campo de selección de promoción -->
          <div class="form-group">
            <label for="id_promocion">Promocion</label>
            <select class="form-control" id="id_promocion" name="id_promocion">
              <option value="">Seleccionar Promocion</option>
              @foreach($promociones as $promo)
                <option value="{{ $promo->id }}" {{ $servicio->id_promocion == $promo->id ? 'selected' : '' }}>{{ $promo->nombre }}</option>
              @endforeach
            </select>
          </div>

          <!-- Botones para cancelar o guardar -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- fin editar servicio -->
