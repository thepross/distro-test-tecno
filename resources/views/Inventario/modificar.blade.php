<!-- Modal Editar Inventario -->
<div class="modal fade" id="editModal{{ $inventario->id }}" tabindex="-1" aria-labelledby="editInventarioLabel{{ $inventario->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Editar Inventario</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('inventario.update', $inventario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" class="form-control"
                               name="descripcion"
                               value="{{ $inventario->descripcion }}" required>
                    </div>

                    <!-- Cantidad -->
                    <div class="mb-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" class="form-control"
                               name="cantidad"
                               value="{{ $inventario->cantidad }}" required>
                    </div>

                    <!-- Fecha -->
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control"
                               name="fecha"
                               value="{{ $inventario->fecha }}" required>
                    </div>

                    <!-- Producto -->
                    <div class="mb-3">
                        <label class="form-label">Producto</label>
                        <select class="form-control" name="id_producto" required>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}"
                                    {{ $inventario->id_producto == $producto->id ? 'selected' : '' }}>
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Tipo -->
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select class="form-control" name="tipo" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="entrada" {{ $inventario->tipo == 'entrada' ? 'selected' : '' }}>Entrada</option>
                            <option value="salida"  {{ $inventario->tipo == 'salida' ? 'selected' : '' }}>Salida</option>
                            <option value="producto terminado"  {{ $inventario->tipo == 'producto terminado' ? 'selected' : '' }}>producto terminado</option>
                        </select>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
