<div class="modal fade" id="deleteModal{{ $privilegio->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $privilegio->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $privilegio->id }}">Desactivar Privilegio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('privilegio.destroy', $privilegio->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro que desea desactivar este privilegio?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Desactivar</button>
                </div>
            </form>
        </div>
    </div>
</div>