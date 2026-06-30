<!-- editar- Nombre-->
<div class="modal fade" id="editNombreModal{{ $datos->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $datos->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $datos->id }}">Editar nombre de la empresa</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarForm" action="{{ route('empresa.nombre', $datos->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la empresa</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $datos->nombre }}" required>
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

<!-- editar- Direccion -->
<div class="modal fade" id="editDireccionModal{{ $datos->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $datos->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $datos->id }}">Editar Direccion</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarForm" action="{{ route('empresa.direccion', $datos->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="direccion" class="form-label">Direccion</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $datos->direccion }}" required>
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

<!-- editar- Correo -->
<div class="modal fade" id="editCorreoModal{{ $datos->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $datos->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $datos->id }}">Editar correo electronico</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarForm" action="{{ route('empresa.correo', $datos->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electronico</label>
                    <input type="text" class="form-control" id="correo" name="correo" value="{{ $datos->correo }}" required>
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

<!-- editar- Telefono -->
<div class="modal fade" id="editTelefonoModal{{ $datos->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $datos->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $datos->id }}">Editar telefono</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarForm" action="{{ route('empresa.telefono', $datos->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $datos->telefono }}" required>
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