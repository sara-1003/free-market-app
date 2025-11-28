<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ProfileRequest;


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

    //商品詳細画面の表示
    public function detail($item_id)
    {
        $item=Item::with(['category','comment.user.profile','favorite'])->findOrFail($item_id);

        $categories = $item->category;
        $comments = $item->comment;
        $favorited = auth()->check() && auth()->user()->favorite->contains($item->id);
        $favoriteCount = $item->favorite->count();
        $commentCount = $comments->count();

        return view('detail',compact('item','categories','comments','favorited','favoriteCount','commentCount'));
    }

    //コメントの追加
    public function store(CommentRequest $request,Item $item)
    {
        Comment::create([
            'item_id'=>$item->id,
            'user_id'=>auth()->id(),
            'comment'=>$request->comment,
        ]);

        return back();
    }

    //商品購入画面の表示
    public function show($item_id)
    {
        $item=Item::findOrFail($item_id);
        $profile=auth()->user()->profile ?? null;

        return view('purchase',compact('item','profile'));
    }
    
    //住所変更ページの表示
    public function edit($item_id)
    {
        $item=Item::findOrFail($item_id);
        $user = auth()->user();

        return view('edit_address',compact('item','user'));
    }
    
    //住所更新
    public function update(ProfileRequest $request, $item_id)
    {
        $user = auth()->user();
        
        $profile = $user->profile ?? $user->profile()->create([]); $profile->post_code = $request->post_code; $profile->address = $request->address; $profile->building = $request->building; $profile->save();

        return redirect()->route('purchase', ['item_id' => $item_id]);
    }
}