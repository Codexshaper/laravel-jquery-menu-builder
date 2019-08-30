<div class="menus">
    <button class="btn btn-lg btn-primary" id="add_menu" data-toggle="modal" data-target="#addMenuModal">Add Menu</button>
    <!-- Modal -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuModalLabel">Add Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" id="menu_form">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" id="menu_name" placeholder="Enter Menu Title">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="menu_submit_btn" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Menu -->
    <div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuModalLabel">Add Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="" id="edit_menu_form">
                    <input type="hidden" name="menu_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" id="edit_menu_name" placeholder="Enter Menu Title">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="edit_menu_submit_btn" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @forelse( $menus as $menu )
        <div class="menu row">
            <div class="menu-name col-md-6" data-id="{{ $menu->id }}">{{ $menu->name  }}</div>
            <div class="menu-actions col-md-6">
                <a href="{{ route('menus.builder',['id'=>$menu->id]) }}" class="menus_builder">Builder</a>
                <a href="#" class="edit_menu_btn" id="edit_menu_btn" data-toggle="modal" data-target="#editMenuModal" data-id="{{ $menu->id  }}">Edit</a>
                <a href="#" class="delete_menu" data-id="{{ $menu->id  }}">Delete</a>
            </div>
        </div>
    @empty
        <p>There is no Menu</p>
    @endforelse
</div>
