@extends('dashboard')

@section('content')
<div class="container">
    <a href="{{ route('menus.detalles.create', $menuId) }}" class="btn btn-primary mb-3">Crear Detalle</a>
    <div class="card table-responsive">
        <div class="card-body">
            <table class="table table-hover" id="detalles">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->id }}</td>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td class="text-end">
                            <a href="{{ route('menus.detalles.edit', [$menuId, $detalle->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('menus.detalles.destroy', [$menuId, $detalle->id]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#detalles').DataTable();
    });
</script>
@endsection
