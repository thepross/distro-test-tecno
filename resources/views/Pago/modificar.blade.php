<!-- editar- -->
<div class="modal fade" id="editModal{{ $pago->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $pago->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $pago->id }}">Editar Pago</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarForm" action="{{ route('pago.update', $pago->id) }}" method="POST">
                @csrf
                @method('PUT')
                @if($usuario->rol->nombre === 'Cajero' || $usuario->rol->nombre === 'Administrador')
                    <div class="form-group">
                        <label for="id_cliente">Cliente</label>
                        <select class="form-control" id="id_cliente" name="id_cliente" required>
                        <option value="">Seleccionar cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ $pago->id_cliente == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="form-group">
                    <label for="id_venta"># Venta</label>
                    <select class="form-control" id="id_venta" name="id_venta" required>
                    <option value="">Seleccionar # venta</option>
                        @foreach($ventas as $venta)
                            <option value="{{ $venta->id }}" {{ $pago->id_venta == $venta->id ? 'selected' : '' }}>
                                {{ $venta->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="metodo" class="form-label">Metodo de pago</label>
                    <input type="text" class="form-control" id="metodo" name="metodo" value="{{ $pago->metodopago }}" required>
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado" name="estado">
                        <option value="a" {{ $pago->estado == 'a' ? 'selected' : '' }}>Activo</option>
                        <option value="i" {{ $pago->estado == 'i' ? 'selected' : '' }}>Inactivo</option>
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