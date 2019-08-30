<?php

if (!function_exists('menu_asset')) {
    function menu_asset($path, $secure = null)
    {
        return route('menu.asset').'?path='.urlencode($path);
    }
}