<a href="{{ route($action['route'], [$row->getKey()]) }}" class="btn btn-danger btn-sm btn-flat" title="Remover registro"
    onclick="event.preventDefault();
        if(confirm('Deseja remover este registro?')){ 
            document.getElementById('form-delete-{{ $row->getKey() }}').submit();
        }">
    Excluir
</a>

<form action="{{ route($action['route'], [$row->getKey()]) }}"
    method="POST"
    id="form-delete-{{ $row->getKey() }}"
    style="display: none;">
    @csrf
    @method('DELETE')
</form>