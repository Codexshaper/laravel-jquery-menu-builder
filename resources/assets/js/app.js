/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
require('nestable2');
window.Swal = require('sweetalert2');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('draggable-menu', require('./components/DraggableMenu.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

(function($){
	// Menus
	$(document).on("click","#menu_submit_btn", function(e){
	    $.ajax({
	        url: '/admin/menu/add',
	        method: 'POST',
	        dataType: 'json',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            'name': $('#menu_name').val(),
	        },
	        success: function(data){
	            // console.log( data );
	            if( data.success == true ) {
	                // Hide Modal
	                // $('#addMenuModal').modal('toggle');
	                $('body').removeClass('modal-open');
	                $('.modal-backdrop').fadeOut();

	                $(".menu-container").html( data.html );

	                Swal.fire(
	                    'Added!',
	                    'Menu Added Successfully',
	                    'success'
	                );
	            }
	        },
	        error: function(err){
	            console.log( err );
	        }
	    });
	});

	$(document).on("click", ".edit_menu_btn", function(e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    $.ajax({
	        url: '/admin/menu/edit',
	        method: 'GET',
	        dataType: 'json',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            'id': id
	        },
	        success: function(data){
	            if( data.success == true ) {
	                // console.log( data );
	                $('#edit_menu_form').attr('data-id', data.menu.id);
	                $('#edit_menu_name').val(data.menu.name);
	            }
	        },
	        error: function(err){
	            console.log( err );
	        }
	    });
	});

	$(document).on("submit","#edit_menu_form", function(e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    $.ajax({
	        url: '/admin/menu/update',
	        method: 'POST',
	        dataType: 'json',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            'id': id,
	            'name': $('#edit_menu_name').val()
	        },
	        success: function(data){
	            console.log( data );

	            if( data.success == true ) {
	                $('.menu-name[data-id = "'+data.menu.id+'"]').text(data.menu.name);
	                // console.log( data.menu.name );
	                $("#editMenuModal").modal('hide');
	                $('body').removeClass('modal-open');
	                $('.modal-backdrop').fadeOut();
	            }
	        },
	        error: function( err ) {
	            console.log(err);
	        }
	    });
	});

	$(document).on("click",".delete_menu", function(e){
	    e.preventDefault();
	    Swal.fire({
	        title: 'Are you sure?',
	        text: 'You will not be able to recover this menu item',
	        type: 'warning',
	        showCancelButton: true,
	        confirmButtonText: 'Yes, delete it!',
	        cancelButtonText: 'No, keep it'
	    }).then((result) => {
	        if (result.value) {
	            $.ajax({
	                url: '/admin/menu/delete',
	                method: 'POST',
	                dataType: 'json',
	                data: {
	                    "_token": "{{ csrf_token() }}",
	                    'id': $(this).data('id'),
	                },
	                success: function(data){
	                    if( data.success == true ) {
	                        // console.log( data );
	                        Swal.fire(
	                            'Deleted!',
	                            'Menu Deleted Successfully',
	                            'success'
	                        );

	                        $(".menu-container").html(data.html);

	                        $('body').removeClass('modal-open');
	                        $('.modal-backdrop').fadeOut();
	                    }
	                },
	                error: function(err){
	                    console.log( err );
	                }
	            });
	        } else if (result.dismiss === Swal.DismissReason.cancel) {
	            Swal.fire(
	                'Cancelled',
	                'Your imaginary file is safe :)',
	                'error'
	            )
	        }
	    })
	});

	/*
	 * Menu Item
	 */

	function initializeNestable( selector = '#nestable' ,options = {} ) {
	    // activate Nestable for list 1
	    $(selector).nestable({
	        group: 1,
	        maxDepth: 10,
	        callback: function(l,e){
	            // l is the main container
	            // e is the element that was moved

	            var list   = l.length ? l : $(l.target);
	            var menus = list.nestable('toArray');
	            $.ajax({
	                url: '/admin/menu/sortItem',
	                method: 'POST',
	                dataType: 'json',
	                data: {
	                    "_token": "{{ csrf_token() }}",
	                    'menus': menus
	                },
	                success: function(data){
	                    if( data.success == true ) {
	                        $("#menu_success").addClass('active').html("Menu Updated Successfully").fadeIn('slow');
	                        setTimeout(function(){
	                            $("#menu_success").fadeOut('slow').html("").removeClass('active');
	                        },3000);
	                    }
	                },
	                error: function(err){
	                    console.log( err );
	                }
	            });
	        }
	    });
	}

	initializeNestable();


	// output initial serialised data
	// updateOutput($('#nestable').data('output', $('#nestable-output')));

	$('#nestable-menu').on('click', function(e)
	{
	    var target = $(e.target),
	        action = target.data('action');
	    if (action === 'expand-all') {
	        $('.dd').nestable('expandAll');
	    }
	    if (action === 'collapse-all') {
	        $('.dd').nestable('collapseAll');
	    }
	});

	$(document).on("submit", "#add_menu_item_form", function(e){
	    e.preventDefault();
	    console.log( "Submit" );
	    var title = $(this).find('#add_menu_item_title').val();
	    var url = $(this).find('#add_menu_item_url').val();
	    var target = $(this).find('#add_menu_item_target').val();
	    var custom_class = $(this).find('#add_menu_item_custom_class').val();
	    var parent_id = $(this).find('#add_parent_id').val();
	    var menu_id = $(this).find('.menu_id').val();

	    $.ajax({
	        url: '/admin/menu/addItem',
	        method: 'POST',
	        dataType: 'json',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            'title': title,
	            'url' : url,
	            'target' : target,
	            'parent_id' : parent_id,
	            'custom_class' : custom_class,
	            'menu_id' : menu_id
	        },
	        success: function(data){
	            // console.log( data );
	            if( data.success == true ) {
	                $(".menu-item-container").html(data.html);
	                initializeNestable();
	                // Hide Modal
	                // $('#addMenuModal').modal('toggle');
	                $('body').removeClass('modal-open');
	                $('.modal-backdrop').fadeOut();

	                Swal.fire(
	                    'Added!',
	                    'Menu Added Successfully',
	                    'success'
	                );

	                // $(this).closest(".dd-item").remove();
	            }
	        },
	        error: function(err){
	            console.log( err );
	        }
	    });
	});

	$(document).on("click",".edit_menu_item", function(e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    var menu_id = $(".menu_id").val();
	    $.ajax({
	        url: '/admin/menu/editItem',
	        method: 'GET',
	        dataType: 'json',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            'id': id,
	            'menu_id': menu_id
	        },
	        success: function(data){
	            if( data.success == true ) {
	                console.log( data );
	                $('#edit_menu_item_form').attr('data-item', data.menuItem.id);
	                $('#edit_menu_item_title').val(data.menuItem.title);
	                $('#edit_menu_item_url').val(data.menuItem.url);
	                $('#edit_menu_item_target').val(data.menuItem.target);
	                $('#edit_menu_item_custom_class').val(data.menuItem.custom_class);
	                if( (data.childrens).length > 0 ){
	                    $.each(data.childrens, function(index, value){
	                        $('#edit_parent_id').find('option[value="'+value.id+'"]').remove();
	                    });
	                }
	                $('#edit_parent_id').find('option[value="'+id+'"]').remove();
	                if( data.menuItem.parent_id ) {
	                    $('#edit_parent_id').val( data.menuItem.parent_id );
	                }
	            }
	        },
	        error: function(err){
	            console.log( err );
	        }
	    });
	});

	$(document).on("submit", "#edit_menu_item_form", function(e){
	    e.preventDefault();
	    var id = $(this).data('item');
	    var title =  $(this).find('#edit_menu_item_title').val();
	    var url = $(this).find("#edit_menu_item_url").val();
	    var parent_id =  $(this).find('#edit_parent_id').val();
	    var target =  $(this).find('#edit_menu_item_target').val();
	    var custom_class =  $(this).find('#edit_menu_item_custom_class').val();
	    var menu_id = $('.menu_id').val();
	    $.ajax({
	        url: '/admin/menu/editItem',
	        method: 'POST',
	        dataType: 'json',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            'id': id,
	            'title': title,
	            'url' : url,
	            'target': target,
	            'custom_class': custom_class,
	            'parent_id': parent_id,
	            'menu_id': menu_id

	        },
	        success: function(data){
	            if( data.success == true ) {
	                // console.log( data );
	                $(".menu-item-container").html(data.html);
	                initializeNestable();
	                // Hide Modal
	                // $('#addMenuModal').modal('toggle');
	                $('body').removeClass('modal-open');
	                $('.modal-backdrop').fadeOut();
	                Swal.fire(
	                    'Updated!',
	                    'Menu Updated Successfully',
	                    'success'
	                );
	            }
	        },
	        error: function(err){
	            console.log( err );
	        }
	    });
	});

	$(document).on("click",".delete_menu_item", function(e){
	    e.preventDefault();
	    Swal.fire({
	        title: 'Are you sure?',
	        text: 'You will not be able to recover this menu item',
	        type: 'warning',
	        showCancelButton: true,
	        confirmButtonText: 'Yes, delete it!',
	        cancelButtonText: 'No, keep it'
	    }).then((result) => {
	        if (result.value) {
	            $.ajax({
	                url: '/admin/menu/deleteItem',
	                method: 'POST',
	                dataType: 'json',
	                data: {
	                    "_token": "{{ csrf_token() }}",
	                    'id': $(this).data('id'),
	                    'menu_id': $('.menu_id').val()
	                },
	                success: function(data){
	                    if( data.success == true ) {
	                        // console.log( data );
	                        Swal.fire(
	                            'Deleted!',
	                            'Menu Deleted Successfully',
	                            'success'
	                        );

	                        $(".menu-item-container").html(data.html);
	                        initializeNestable();

	                        // $(this).closest(".dd-item").remove();
	                    }
	                },
	                error: function(err){
	                    console.log( err );
	                }
	            });
	        } else if (result.dismiss === Swal.DismissReason.cancel) {
	            Swal.fire(
	                'Cancelled',
	                'Your imaginary file is safe :)',
	                'error'
	            )
	        }
	    })
	});
})(jQuery);