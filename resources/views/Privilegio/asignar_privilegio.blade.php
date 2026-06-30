{{-- Modal Asignar Privilegios --}}
<div class="modal fade" id="asignarPrivilegio{{ $rol->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('privilegios.asignar', $rol->id) }}">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Asignar Privilegios a: {{ $rol->nombre }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <table class="table table-sm table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Funcionalidad</th>
                                <th>Agregar</th>
                                <th>Borrar</th>
                                <th>Modificar</th>
                                <th>Leer</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($funcionalidades as $func)
                                @php
                                    $p = $rol->privilegiosAgrupados[$func] ?? null;
                                @endphp
                                <tr>
                                    <td>{{ $func }}</td>

                                    <td><input type="checkbox" name="privilegios[{{ $func }}][agregar]" {{ $p && $p->agregar ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="privilegios[{{ $func }}][borrar]" {{ $p && $p->borrar ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="privilegios[{{ $func }}][modificar]" {{ $p && $p->modificar ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="privilegios[{{ $func }}][leer]" {{ $p && $p->leer ? 'checked' : '' }}></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar Cambios</button>
                </div>

            </div>
        </form>
    </div>
</div>
