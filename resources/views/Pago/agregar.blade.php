<!-- Modal Agregar Pago -->
<div class="modal fade" id="agregarModal" tabindex="-1" aria-labelledby="agregarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarModalLabel">Agregar Pago</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('pago.store') }}" method="POST">
          @csrf
          @if($usuario->rol->nombre === 'Cajero' || $usuario->rol->nombre === 'Administrador')
          <div class="form-group">
            <label for="id_cliente">Cliente</label>
            <select class="form-control" id="id_cliente" name="id_cliente" required>
              <option value="">Seleccionar cliente</option>
              @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="form-group">
              <label for="id_venta"># Venta</label>
              <select class="form-control" id="id_venta" name="id_venta" required>
              <option value="">Seleccionar venta</option>
              @foreach($ventas as $venta)
              <option value="{{ $venta->id }}">{{ $venta->id }}</option>
              @endforeach
              </select>
          </div>
          
          <div class="form-group">
            <label for="metodo">Método de Pago</label>
            <select class="form-control" id="metodo" name="metodo" required>
            <option value="">Seleccionar Metodo de pago</option>
              <option value="Pago-Facil">Tigo Money</option>
              <option value="Efectivo">Efectivo</option>
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

<!-- Agregar esta sección de script en tu vista -->




