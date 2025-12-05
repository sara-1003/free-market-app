<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;



class AuthController extends Controller
{
public function index(Request $request)
{
    $tab = $request->tab;

    // 検索保持
    $keyword=session()->get('search_keyword');

    if(!empty($keyword)){
        if($tab==='mylist' && auth()->check()){
            $items=auth()->user()
            ->favoriteItems()
            ->where('name','like',"%{$keyword}%")
            ->latest()
            ->get();
        }else{
            $items=Item::keywordSearch($keyword)->get();
            $tab='best';
        }

        return view('index',compact('items','tab'));
    }

    // ログイン済みかつタブ指定なしならマイリスト
    if ($tab === null && auth()->check()) {
        $tab = 'mylist';
    }

    if ($tab === 'mylist' && auth()->check()) {
        // マイリスト表示（ログイン済みのみ）
        $items = auth()->user()->favoriteItems()->latest()->get();
    } else {
        // それ以外はおすすめを表示
        $tab = 'best';
        $items = Item::latest()->get();
    }

    return view('index', compact('items', 'tab'));
}

    // headerの検索機能
    public function search(Request $request)
    {
// 検索保持
        $keyword=$request->keyword;
        if(!empty($keyword)){
            session()->put('search_keyword',$keyword);
        }


        $items=Item::keywordSearch($request->keyword)->get();

        $tab='best';

        return view('index',compact('items','tab'));
    }
}