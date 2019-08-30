<li class='dd-item' data-order={{$menu->order}} data-id={{$menu->id}}>
    <div class='dd-handle'>
        <i class='fas fa-arrows-alt'></i>
        <div class='content'>{{$menu->title}}</div>
    </div>
    <div class='menu-action'>
        <a class='edit_menu_item' href="#"  data-toggle="modal" data-target="#editMenuModal" data-id="{{ $menu->id  }}">Edit</a> |
        <a href='#' class='delete_menu_item text-danger' data-id="{{$menu->id}}">Delete</a>
    </div>
    @if (count($menu->children) > 0)
        <ol class="dd-list">
            @foreach($menu->children as $menu)
                @include('menu::menus.recursive', $menu)
            @endforeach
        </ol>
    @endif
</li>
