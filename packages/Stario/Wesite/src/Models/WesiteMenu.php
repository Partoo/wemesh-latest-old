<?php
namespace Stario\Wesite\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class WesiteMenu extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
        'sort_scope' => 'type',
    ];
    protected $guarded = ['id'];

    protected $table = 'wesite_menus';

    public function scopeMain($query)
    {
        return $query->where('type', '=', 0);
    }

    public function scopeQuick($query)
    {
        return $query->where('type', '=', 1);
    }

}
