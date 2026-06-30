<div class="modal fade" id="editModal{{ $privilegio->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $privilegio->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $privilegio->id }}">Modificar Privilegio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('privilegio.update', $privilegio->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rol_id">Rol</label>
                        <select class="form-control" id="rol_id" name="rol_id" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}" {{ $privilegio->id_rol == $rol->id ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="funcion">Funcionalidad</label>
                        <input type="text" class="form-control" id="funcion" name="funcion" value="{{ $privilegio->funcionalidad }}" required>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agregar" name="agregar" {{ $privilegio->agregar ? 'checked' : '' }}>
                            <label class="form-check-label" for="agregar">Agregar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="borrar" name="borrar" {{ $privilegio->borrar ? 'checked' : '' }}>
                            <label class="form-check-label" for="borrar">Borrar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="modificar" name="modificar" {{ $privilegio->modificar ? 'checked' : '' }}>
                            <label class="form-check-label" for="modificar">Modificar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="leer" name="leer" {{ $privilegio->leer ? 'checked' : '' }}>
                            <label class="form-check-label" for="leer">Leer</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="a" {{ $privilegio->estado == 'a' ? 'selected' : '' }}>Activo</option>
                            <option value="i" {{ $privilegio->estado == 'i' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>