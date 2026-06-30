@extends('dashboard')

@section('content')
<div class="container">
    <h2>Crear Detalle del Menú</h2>
    <form action="{{ route('menus.detalles.store', $menuId) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="id_producto" class="form-label">Producto</label>
            <select class="form-control" id="id_producto" name="id_producto" required>
                @foreach($productos as $producto)
                <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="a">Activo</option>
                <option value="i">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
</div>
@endsection
