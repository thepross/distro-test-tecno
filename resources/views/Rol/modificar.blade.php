<!-- editar-rol -->
<div class="modal fade" id="editModal{{ $rol->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $rol->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $rol->id }}">Editar Rol</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarForm" action="{{ route('rol.update', $rol->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $rol->nombre }}">
                </div>
                
                <div class="mb-3">
                    <label for="state" class="form-label">Estado</label>
                    <select class="form-control" id="state" name="state">
                        <option value="">Seleccionar Estado</option>
                        <option value="a" {{ $rol->state === 'a' ? 'selected' : '' }}>Activo</option>
                        <option value="i" {{ $rol->state === 'i' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                
                <!-- ... Código posterior ... -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>