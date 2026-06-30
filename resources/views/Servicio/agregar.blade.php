D<!-- ... Código Agregad servicio ... -->
<div class="modal fade" id="agregarModal" tabindex="-1" aria-labelledby="agregarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarModalLabel">Agregar Servicio</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('servicio.store') }}" method="POST">
          @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>
            <div class="mb-3">
                <label for="duracion" class="form-label">Duracion</label>
                <input type="numbre" class="form-control" id="duracion" name="duracion" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="numbre" class="form-control" id="precio" name="precio" required>
            </div>
             <div class="form-group">
                <label for="promo">Promocion</label>
                <select class="form-control" id="promo" name="promo">
                        <option value="">Seleccionar Promocion</option>
                    @foreach($promociones as $promo)
                        <option value="{{ $promo->id }}">{{ $promo->nombre }}</option>
                    @endforeach
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
<!-- ... end agregar servicio ... -->