<?php

namespace CodexShaper\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use CodexShaper\Menu\Models\MenuItem;
class Menu extends Model
{
    //

    public function items(){
        return $this->hasMany(MenuItem::class, 'menu_id');
    }
}
