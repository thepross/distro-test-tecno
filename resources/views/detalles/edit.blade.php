@extends('dashboard')

@section('content')
<div class="container">
    <h2>Editar Detalle del Menú</h2>
    <form action="{{ route('menus.detalles.update', [$menuId, $detalle->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="id_producto" class="form-label">Producto</label>
            <select class="form-control" id="id_producto" name="id_producto" required>
                @foreach($productos as $producto)
                <option value="{{ $producto->id }}" {{ $detalle->id_producto == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="a" {{ $detalle->estado == 'a' ? 'selected' : '' }}>Activo</option>
                <option value="i" {{ $detalle->estado == 'i' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
