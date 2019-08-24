<span class="text-right">
    <form action="{{ url()->current() }}" method="get" class="form-inline">
        <div class="input-group">            
            <input type="text" name="search" class="form-control" placeholder="Pesquisar..." value="{{ \Request::get('search') }}">
        </div>
        <button type="submit" class="btn btn-success btn-flat">Pesquisar</button>
    </form>
</span>

@if (count($dataGrid->rows()))

    <hr>

    <table class="table table-striped table-bordered" id="table-search">   
        <tr>
            <th data-name="id">ID</th>

            @foreach ($dataGrid->getColumns() as $column)
                <th width="{{ $column['width'] }}">
                    {{ $column['label'] }}
                    @if (isset($column['_order']) && $column['order'] != false)
                        @php
                            $icons = [
                                1       => 'fa-sort',
                                'asc'   => 'fa-sort-up',
                                'desc'  => 'fa-sort-down'
                            ];
                        @endphp
                        <a href="javascript:void(0)" class="sort" data-name="{{ $column['name'] }}">
                            &nbsp;&nbsp;<i class="fa {{ $icons[$column['_order']] }}"></i>
                        </a>
                    @endif
                </th>
            @endforeach

            @if (count($dataGrid->actions()))
                <th>Ações</th>
            @endif
        </tr>
        @foreach ($dataGrid->rows() as $row)
            <tr>
                <td>{{ $row->id }}</td>

                @foreach ($dataGrid->getColumns() as $column)
                    <td>
                        @if (strpos($column['name'], '.'))
                            @php
                                $array = explode(".", $column['name']);
                                $relation = $array[0];
                                $name = $array[1];
                            @endphp
                            {!! $row->$relation->$name !!}
                        @else
                            {!! $row->{$column['name']} !!}
                        @endif                       
                    </td>
                @endforeach

                @if (count($dataGrid->actions()))
                    <td>
                        @foreach ($dataGrid->actions() as $action)                    
                            @include($action['view'], [
                                'row' => $row,    
                                'action' => $action
                            ])
                        @endforeach
                    </td>
                @endif
            </tr>
        @endforeach        
    </table>

    <div class="box-footer clearfix">
        {!! $dataGrid->rows()->appends(['field_order' => \Request::get('field_order'), 'order' => \Request::get('order'), 'search' => \Request::get('search')])->links() !!}
    </div>

@else
    <h4 class="text-danger">Nenhum registro encontrado</h4>
@endif

@push('js')
    <script>
        $(document).ready(function () {
            $('.sort').click(function (){
                var anchor = $(this);
                var field = anchor.attr('data-name');
                var order = anchor.find('i').hasClass('fa-sort-down') || anchor.find('i').hasClass('fa-sort') ? 'asc' : 'desc';
                var url = "{{ url()->current() }}?";

                @if(\Request::get('page'))
                    url += "page={{\Request::get('page')}}&";
                @endif
                @if(\Request::get('search'))
                    url += "page={{\Request::get('search')}}&";
                @endif
                url +='field_order='+field+'&order='+order;

                window.location = url;

                // alert(order);

            });
        });
    </script>
@endpush
