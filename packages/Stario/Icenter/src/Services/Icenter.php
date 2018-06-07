<?php
namespace Stario\Icenter\Services;

use Illuminate\Support\Facades\Storage;
use Stario\Icenter\Services\Traits\MakeMenu;

/**
 * Icenter静态方法
 */
class Icenter
{
    use MakeMenu;

    public static function makeMenus()
    {

        foreach (glob(storage_path() . '/icenter/menus/*.json') as $fileName) {
            static::makeMenuFromJson(Storage::disk('icenter')->get('menus/' . basename($fileName)));
        }

    }
}
