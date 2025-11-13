<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    // user 多対１
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // category_item 1対多 中間
    public function category()
    {
        return $this->belongsToMany(
            Category::class,
            'category_items',
            'item_id',
            'category_id'
        );
    }

    // favorite 1対多
    public function favorite()
    {
        return $this->hasMany(Favorite::class);
    }

    // comment 1対多
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    // order 1対多
    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
