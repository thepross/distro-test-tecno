<div class="modal fade" id="agregarHorarioModal" tabindex="-1" role="dialog" aria-labelledby="agregarHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarHorarioModalLabel">Agregar Privilegio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('privilegio.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rol_id">Rol</label>
                        <select class="form-control" id="rol_id" name="rol_id" required>
                               <option value="">Seleccionar Rol</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}">
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="funcion">Funcionalidad</label>
                        <input type="text" class="form-control" id="funcion" name="funcion" required>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agregar" name="agregar">
                            <label class="form-check-label" for="agregar">Agregar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="borrar" name="borrar">
                            <label class="form-check-label" for="borrar">Borrar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="modificar" name="modificar">
                            <label class="form-check-label" for="modificar">Modificar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="leer" name="leer">
                            <label class="form-check-label" for="leer">Leer</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>