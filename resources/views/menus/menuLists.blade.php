<div class="menu-header">
    <div class="row">
        <div class="col-md-4"><button class="btn btn-lg btn-primary" id="add_menu_item" data-toggle="modal" data-target="#addMenuModal">Add Item</button></div>
        <div class="col-md-8"><div class="alert-success text-dark-50" id="menu_success"></div></div>
    </div>

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
                <form method="post" action="" id="add_menu_item_form">
                    <input type="hidden" name="menu_id" class="menu_id" value="{{ $menu->id  }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add_menu_item_title">Title</label>
                            <input type="text" name="title" class="form-control" id="add_menu_item_title" placeholder=" Menu Item Title">
                        </div>
                        <div class="form-group">
                            <label for="add_menu_item_url">URL</label>
                            <input type="text" name="url" class="form-control" id="add_menu_item_url" placeholder="Menu Item URL">
                        </div>
                        <div class="form-group">
                            <label for="add_menu_item_target">Open In</label>
                            <select name="target" id="add_menu_item_target" class="form-control">
                                <option value="_self">Same Tab</option>
                                <option value="_blank">New Tab</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_parent_id">Parent</label>
                            <select name="parent_id" id="add_parent_id" class="form-control">
                                <option value="">Select parent</option>
                                @forelse( $parents as $parent )
                                    <option value="{{ $parent->id  }}">{{ $parent->title  }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_menu_item_custom_class">Custom Class</label>
                            <input type="text" name="custom_class" class="form-control" id="add_menu_item_custom_class" placeholder="Custom Class Name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="add_menu_item_btn" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuModalLabel">Edit Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="" id="edit_menu_item_form">
                    <input type="hidden" name="menu_id" class="menu_id" value="{{ $menu->id  }}">
                    <div class="modal-body">
                            <div class="form-group">
                                <label for="edit_menu_item_title">Title</label>
                                <input type="text" name="title" class="form-control" id="edit_menu_item_title" placeholder="Menu Title">
                            </div>
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" name="url" class="form-control" id="edit_menu_item_url" placeholder="Menu Item URL">
                            </div>
                            <div class="form-group">
                                <label for="edit_menu_item_target">Open In</label>
                                <select name="target" id="edit_menu_item_target" class="form-control">
                                    <option value="_self">Same Tab</option>
                                    <option value="_blank">New Tab</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_parent_id">Parent</label>
                                <select name="parent_id" id="edit_parent_id" class="form-control">
                                    <option value="">Select parent</option>
                                    @forelse( $parents as $parent )
                                        <option value="{{ $parent->id  }}">{{ $parent->title  }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="add_menu_item_custom_class">Custom Class</label>
                                <input type="text" name="custom_class" class="form-control" id="edit_menu_item_custom_class" placeholder="Custom Class">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="edit_menu_item_btn" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if (count($menus) > 0)
    <div class="dd" id="nestable">
    <ol class="dd-list">
        @foreach ($menus as $menu)
            @include('menu::menus.recursive', $menu)
        @endforeach
    </ol>
    </div>
@endif
