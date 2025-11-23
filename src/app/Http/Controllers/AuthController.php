<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;



class AuthController extends Controller
{
    public function index(Request $request)
    {
        if ($request->tab === 'mylist' && auth()->check()) {
        // ログイン済みならマイリストを取得
            $items = auth()->user()->favoriteItems()->latest()->get();
        } else {
        // そうでない場合は通常商品一覧
            $items = Item::latest()->get();
        }

        return view('index', compact('items'));
    }

    // headerの検索機能
    public function search(Request $request)
    {
        $items=Item::keywordSearch($request->keyword)->get();

        return view('index',compact('items'));
    }
}
