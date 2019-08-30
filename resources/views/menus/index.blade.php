@extends('menu::layouts.app')

@section('content')
    <div class="container">
        <div class="menu-container">
            @include('menu::menus.partials.menuLists', $menus)
        </div>
    </div>
@endsection
