<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;


class ItemController extends Controller
{
    //出品画面の表示
    public function index()
    {
        $user=Auth()->user();
        $categories = Category::all();

        return view('sell',compact('user','categories'));
    }

    //商品出品ボタン
    public function sell(ExhibitionRequest $request)
    {
        $user = Auth()->user();
    
        // データまとめて取得
        $itemData = $request->only([
            'name',
            'brand',
            'description',
            'condition',
            'price',
        ]);
    
        // user_id を追加
        $itemData['user_id'] = $user->id;

        // sold の初期値を追加
        $itemData['sold'] = false;
    
        // 画像保存
        if ($request->hasFile('img')) {
            $itemData['img'] = $request->file('img')->store('items', 'public');
        } else {
            $itemData['img'] = null;
        }
    
        // 商品登録
        $item = Item::create($itemData);
    
        // カテゴリ紐付け
        if ($request->filled('category_ids')) {
            $item->category()->sync($request->category_ids);
        }
    
        return redirect('/mypage');
    }
}