{{-- https://laracasts.com/discuss/channels/laravel/categories-tree-view/replies/114604 --}}

<ul>
@php
    $items = $root ? $items->where('parent_id', 0) : $items;
@endphp
@foreach($items as $key => $item)
    @if (gettype($item) == 'object')
        <li id="ids[{{$item->id}}]"
            class="f-s-14 p-t-5 {{in_array($item->id, $undeletables) ? 'nocheck' : ''}}
                {{ in_array($item->id, $undeletables) && $item->parent_id ? 'p-l-25' : ''}}"
            data-jstree='{"type":"{!! $view_var[$item->id]->icon_type !!}" }'>

            {!! HTML::linkRoute("$route_name.edit", $item->view_index_label(), $item->id) !!}

            @if($view_var[$item->id]->children_count > 0)
                @include('Generic.tree_item', [
                    'items' => $view_var[$item->id]->children,
                    'color' => $color++,
                    'root' => false
                ])
            @endif
        </li>
    @else
        <li class="f-s-14 p-t-5 nocheck" data-jstree='{"type":"default-1" }'>
        @if(is_array($item))
            {{$key}}
            @include('Generic.tree_item', array('items' => $item))
        @else
            {!! HTML::linkRoute('Modem.index', "$key: $item", ['modem_show_filter' => $key]) !!}
        @endif
    @endif
@endforeach
</ul>
