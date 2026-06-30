<div class="modal fade" id="agregarModal" tabindex="-1" aria-labelledby="agregarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> {{-- modal grande --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Venta</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('venta.store') }}" method="POST" id="ventaForm">
                    @csrf

                    {{-- DATOS DE LA VENTA --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Cliente</label>
                            <select name="cliente_id" class="form-control" required>
                                <option value="">Seleccionar cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>

                    {{-- SELECCIÓN PRODUCTOS Y SERVICIOS --}}
                    <div class="row mb-3">

                        <div class="col-md-4">
                            <label>Producto</label>
                            <select id="producto_id" class="form-control">
                                <option value="">Seleccionar producto</option>
                                @foreach ($productos as $p)
                                    <option value="{{ $p->id }}"
                                        data-precio="{{ $p->precioConDescuento() }}">
                                        {{ $p->nombre }} — {{ number_format($p->precioConDescuento(),2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Servicio</label>
                            <select id="servicio_id" class="form-control">
                                <option value="">Seleccionar servicio</option>
                                @foreach ($servicios as $s)
                                    <option value="{{ $s->id }}"
                                        data-precio="{{ $s->precioConDescuento() }}">
                                        {{ $s->descripcion }} — {{ number_format($s->precioConDescuento(),2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Cantidad</label>
                            <input type="number" id="cantidad" value="1" min="1" class="form-control">
                        </div>

                        <div class="col-md-2 align-self-end">
                            <button type="button" class="btn btn-success w-100" id="agregar-detalle">
                                <i class="fa fa-plus"></i> Agregar
                            </button>
                        </div>
                    </div>

                    {{-- TABLA DETALLES --}}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>P/U</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabla-detalles"></tbody>

                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">TOTAL:</th>
                                <th id="total-general">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- campo hidden donde se manda el json --}}
                    <input type="hidden" name="detalles" id="detalles-input">

                    <button class="btn btn-primary w-100">Guardar Venta</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let detalles = [];

const tabla = document.getElementById('tabla-detalles');
const totalGeneral = document.getElementById('total-general');

document.getElementById('agregar-detalle').addEventListener('click', () => {

    const productoId = document.getElementById('producto_id').value;
    const servicioId = document.getElementById('servicio_id').value;
    const cantidad = parseInt(document.getElementById('cantidad').value);

    if (!productoId && !servicioId) {
        alert("Seleccione un producto o servicio.");
        return;
    }

    let tipo = "";
    let nombre = "";
    let precio = 0;

    if (productoId) {
        const opt = document.querySelector(`#producto_id option[value="${productoId}"]`);
        tipo = "Producto";
        nombre = opt.text.split("—")[0].trim();
        precio = parseFloat(opt.dataset.precio);
    }

    if (servicioId) {
        const opt = document.querySelector(`#servicio_id option[value="${servicioId}"]`);
        tipo = "Servicio";
        nombre = opt.text.split("—")[0].trim();
        precio = parseFloat(opt.dataset.precio);
    }

    const subtotal = precio * cantidad;

    // Agregar fila tabla
    const row = tabla.insertRow();
    row.innerHTML = `
        <td>${tipo}</td>
        <td>${nombre}</td>
        <td>${cantidad}</td>
        <td>${precio.toFixed(2)}</td>
        <td class="subtotal">${subtotal.toFixed(2)}</td>
        <td><button class="btn btn-danger btn-sm eliminar">X</button></td>
    `;

    // Guardar detalle
    detalles.push({
        producto_id: productoId || null,
        servicio_id: servicioId || null,
        cantidad: cantidad,
    });

    actualizarTotal();

    // limpiar selección
    document.getElementById('producto_id').value = "";
    document.getElementById('servicio_id').value = "";
    document.getElementById('cantidad').value = 1;

    row.querySelector(".eliminar").addEventListener("click", () => {
        const index = row.rowIndex - 1;
        detalles.splice(index, 1);
        row.remove();
        actualizarTotal();
    });
});

function actualizarTotal() {
    let t = 0;
    document.querySelectorAll(".subtotal").forEach(s => {
        t += parseFloat(s.textContent);
    });

    totalGeneral.textContent = t.toFixed(2);
    document.getElementById('detalles-input').value = JSON.stringify(detalles);
}

document.getElementById('ventaForm').addEventListener('submit', function() {
    document.getElementById('detalles-input').value = JSON.stringify(detalles);
});
</script>
