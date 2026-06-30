<!-- Editar Usuario Modal -->
<div class="modal fade" id="editModal{{ $usuario->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $usuario->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel{{ $usuario->id }}">Editar Usuario</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('usuario.update', $usuario->id) }}" method="POST">
          @csrf
          @method('PUT') <!-- Método PUT para actualizar el recurso -->
          
          <!-- Campo de nombre -->
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $usuario->nombre }}" required>
          </div>

          <!-- Campo de teléfono -->
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="number" class="form-control" id="telefono" name="telefono" value="{{ $usuario->telefono }}" required>
          </div>

          <!-- Campo de correo electrónico -->
          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}" required>
          </div>

          <!-- Campo de rol -->
          <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-control" id="rol" name="rol" required>
              <option value="">Seleccionar Rol</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $usuario->id_rol == $role->id ? 'selected' : '' }}>
                  {{ $role->nombre }}
                </option>
              @endforeach
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
<!-- End Editar Usuario Modal -->
