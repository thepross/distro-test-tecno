<!-- Modal Editar Producto -->
<div class="modal fade" id="editModal{{ $producto->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $producto->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $producto->id }}">Editar Producto</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('producto.update', $producto->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre{{ $producto->id }}" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre{{ $producto->id }}" 
                               name="nombre" value="{{ $producto->nombre }}" required>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcion{{ $producto->id }}" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="descripcion{{ $producto->id }}" 
                               name="descripcion" value="{{ $producto->descripcion }}" required>
                    </div>

                    <!-- Fecha ingreso -->
                    <div class="mb-3">
                        <label for="fecha_ingreso{{ $producto->id }}" class="form-label">Fecha ingreso</label>
                        <input type="date" class="form-control" id="fecha_ingreso{{ $producto->id }}" 
                               name="fecha_ingreso" value="{{ $producto->fecha_ingreso }}" required>
                    </div>

                    <!-- Precio -->
                    <div class="mb-3">
                        <label for="precio{{ $producto->id }}" class="form-label">Precio</label>
                        <input type="number" step="0.01" class="form-control" id="precio{{ $producto->id }}" 
                               name="precio" value="{{ $producto->precio }}" required>
                    </div>

                    <!-- Stock -->
                    <div class="mb-3">
                        <label for="stock{{ $producto->id }}" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock{{ $producto->id }}" 
                               name="stock" value="{{ $producto->stock }}" required>
                    </div>

                    <!-- Categoría -->
                    <div class="form-group mb-3">
                        <label for="id_categoria{{ $producto->id }}">Categoría</label>
                        <select class="form-control" id="id_categoria{{ $producto->id }}" name="id_categoria" >
                            <option value="">Seleccionar Categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" 
                                    {{ $producto->id_categoria == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Promoción -->
                    <div class="form-group mb-3">
                        <label for="id_promocion{{ $producto->id }}">Promoción</label>
                        <select class="form-control" id="id_promocion{{ $producto->id }}" name="id_promocion">
                            <option value="">Sin promoción</option>
                            @foreach($promociones as $promo)
                                <option value="{{ $promo->id }}" 
                                    {{ $producto->id_promocion == $promo->id ? 'selected' : '' }}>
                                    {{ $promo->nombre }}
                                </option>
                            @endforeach
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
