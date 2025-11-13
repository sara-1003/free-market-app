<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];


    //category_item １対多　中間
    public function item()
    {
        return $this->belongsToMany(
            Item::class,
            'category_items',
            'category_id',
            'item_id',
        );
    }
}
