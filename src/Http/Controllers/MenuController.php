<?php

namespace CodexShaper\Menu\Http\Controllers;

use CodexShaper\Menu\Models\Menu;
use CodexShaper\Menu\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    protected $order = [];
    protected $childrens = [];
    public function index() {
        $menus = Menu::all();
        return view('menu::menus.index', compact('menus'));
    }

    public function addMenu( Request $request ) {
        if( $request->ajax() ) {
            $menu = new Menu;
            $menu->name = $request->name;
            if( $menu->save() ) {
                $menus = Menu::all();
                $html =  view('menu::menus.partials.menuLists', compact('menus'))->render();
                return response()->json([
                    'success' => true,
                    'html' => $html
                ]);
            }
        }
        return response()->json([
            'success' => false
        ]);
    }

    public function editMenu( Request $request ) {
        if( $request->ajax() ){
            if( $menu = Menu::find( $request->id ) ) {
                return response()->json([
                    'success' => true,
                    'menu' => $menu
                ]);
            }
        }
        return response()->json([
            'success' => false
        ]);
    }

    public function updateMenu( Request $request ) {
        if( $request->ajax() ) {
            if( $menu = Menu::find( $request->id ) ) {
                $menu->name = $request->name;
                if( $menu->update() ) {
                    return response()->json([
                        'success' => true,
                        'menu' => $menu
                    ]);
                }
            }
        }
        return response()->json(['success' => false]);
    }

    public function deleteMenu( Request $request ){
        if($request->ajax()) {
            if( $menu = Menu::find($request->id) ) {
                if( $menu->delete() ){
                    $menus = Menu::all();
                    $html =  view('menu::menus.partials.menuLists', compact('menus'))->render();
                    return response()->json([
                        'success' => true,
                        'html' => $html
                    ]);
                }
            }
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function getMenuItems( Request $request )
    {
        $menus = $this->getMenuItemWithChildrens($request->id);
        $parents = MenuItem::where('menu_id', $request->id)->get();
        $menu = Menu::find($request->id);
        return view('menu::menus.builder', compact('menus','parents','menu'));
    }

    public function addMenuItem( Request $request ) {
        if( $request->ajax() ) {
            $title = $request->title;
            $parent_id = isset($request->parent_id) ? $request->parent_id : null;

            if( $parent_id ) {
                $order = MenuItem::where('parent_id', $parent_id)->max('order');
            }else {
                $order = MenuItem::whereNull('parent_id')->max('order');
            }

            $menuItem = new MenuItem;
            $menuItem->menu_id = $request->menu_id;
            $menuItem->title = $title;
            $menuItem->slug = Str::slug($title);
            $menuItem->url = $request->url;
            $menuItem->target = $request->target;
            $menuItem->parent_id = $parent_id;
            $menuItem->order = $order + 1;
            $menuItem->custom_class = $request->custom_class;
            if( $menuItem->save() ) {
                $menus = $this->getMenuItemWithChildrens($request->menu_id);
                $parents = MenuItem::where('menu_id', $request->menu_id)->get();
                $menu = Menu::find( $request->menu_id );
                $html =  view('menu::menus.menuLists', compact('menus', 'parents','menu'))->render();
                return response()->json([
                    'success' => true,
                    'html' => $html,
                ]);
            }
        }
        return response()->json([
            'success' => false
        ]);
    }

    public function sortMenuItem( Request $request ){
        if( $request->ajax() ) {
            $items = $request->menus;
            foreach( $items as $item ) {
                $menuItem = MenuItem::find( $item['id'] );
                $parent_id = isset($item['parent_id']) ? $item['parent_id'] : null;
                if( $parent_id ) {
                    $this->order[ $parent_id ] = isset( $this->order[$parent_id] ) ?  $this->order[$parent_id] + 1 : 1;
                    $newOrder = $this->order[ $parent_id ];
                }else {
                    $this->order['root'] = isset( $this->order['root'] ) ?  $this->order['root'] + 1 : 1;
                    $newOrder = $this->order['root'];
                }
                $menuItem->parent_id  = $parent_id;
                $menuItem->order  =  $newOrder;
                $menuItem->update();
            }
            return response()->json([
                'success' => true,
                'orders' => $this->order
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function editMenuItem( Request $request ) {
        if( $request->ajax() ) {
            if( isset( $request->id ) ) {
                $menu_id = $request->menu_id;
                if ( $menuItem = MenuItem::find($request->id) ) {
                    $childrens = MenuItem::with('childrens')->where(['menu_id' => $menu_id, 'parent_id' => $request->id])->orderBy('order', 'asc')->get();
                    $this->getSingleDimentionChildrens($childrens);
                    return response()->json([
                        'success' => true,
                        'menuItem' => $menuItem,
                        'childrens' => $this->childrens
                    ]);
                }
            }
        }
        return response()->json([
            'success' => false
        ]);
    }

    public function getSingleDimentionChildrens($array) {
        if( empty( $array )) {
            return false;
        }
        foreach( $array as $value ) {
            $this->childrens[] = $value;
            if( !empty($value['childrens']) ) {
                $this->getSingleDimentionChildrens( $value['childrens'] );
            }
        }
    }

    public function updateMenuItem( Request $request ) {
        if($request->ajax()) {
            if( $menuItem = MenuItem::find($request->id) ) {
                $menuItem->title = $request->title;
                $menuItem->slug = Str::slug($request->title);
                $menuItem->url = $request->url;
                $menuItem->target = $request->target;
                $menuItem->parent_id = $request->parent_id;
                $menuItem->custom_class = $request->custom_class;
                if( $menuItem->update() ){
                    $menus = $this->getMenuItemWithChildrens($request->menu_id);
                    $parents = MenuItem::where('menu_id', $request->menu_id)->get();
                    $menu = Menu::find( $request->menu_id );
                    $html =  view('menu::menus.menuLists', compact('menus','parents','menu'))->render();
                    return response()->json([
                        'success' => true,
                        'html' => $html,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function destroyMenuItem( Request $request ) {
        if($request->ajax()) {
            if( $menuItem = MenuItem::find($request->id) ) {
                if( $childrens = $menuItem->childrens ) {
                    foreach ($childrens as $children) {
                        $child = MenuItem::find($children->id);
                        $child->parent_id = $menuItem->parent_id;
                        $child->save();
                    }
                }
                if( $menuItem->delete() ){
                    $menus = $this->getMenuItemWithChildrens($request->menu_id);
                    $parents = MenuItem::where('menu_id', $request->menu_id)->get();
                    $menu = Menu::find( $request->menu_id );
                    $html =  view('menu::menus.menuLists', compact('menus','parents','menu'))->render();
                    return response()->json([
                        'success' => true,
                        'html' => $html,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function getMenuItemWithChildrens( $menu_id ) {
        return MenuItem::with('childrens')->where('menu_id', $menu_id)->whereNull('parent_id')->orderBy('order', 'asc')->get();
    }

    public function assets(Request $request)
    {
        $file = base_path('package/laravel-menu-builder/publishable/assets/'.urldecode($request->path));

        if (File::exists($file)) {
            
            switch ( $extension = pathinfo($file, PATHINFO_EXTENSION) ) {
                case 'js':
                    $mimeType = 'text/javascript';
                    break;
                case 'css':
                    $mimeType = 'text/css';
                    break;
                default:
                    $mimeType = File::mimeType($file);
                    break;
            }

            $response = Response::make(File::get($file), 200);
            $response->header('Content-Type', $mimeType);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));
            
            return $response;
        }

        return response('', 404);
    }
}
