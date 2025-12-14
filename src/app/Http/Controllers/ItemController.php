<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Order;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;


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

        $itemData['user_id'] = $user->id;

        $itemData['sold'] = false;
        // 画像保存
        if ($request->hasFile('img')) {
            $itemData['img'] = $request->file('img')->store('items', 'public');
        } else {
            $itemData['img'] = null;
        }
        $item = Item::create($itemData);

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
        $favorited = auth()->check()
        ? $item->favorite()->where('user_id', auth()->id())->exists()
        : false;
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
        $user=auth()->user();
        $item=Item::findOrFail($item_id);

        return view('edit_address',compact('user','item'));
    }

    //住所更新
    public function update(AddressRequest $request,$item_id)
    {
        $user=auth()->user();

        session([
            'order_post_code' => $request->post_code,
            'order_address'   => $request->address,
            'order_building'  => $request->building,
    ]);
    return redirect('/purchase/' . $item_id);

    }

    //商品購入ボタンの実装
    public function purchase(PurchaseRequest $request, $item_id)
{
    $user = auth()->user();
    $item = Item::findOrFail($item_id);

    if ($item->sold) {
        abort(403, 'この商品はすでに購入されています。');
    }

    Order::create([
        'user_id' => $user->id,
        'item_id' => $item->id,
        'payment_method' => $request->payment_method,
        'post_code' => session('order_post_code', $user->profile->post_code),
        'address' => session('order_address', $user->profile->address),
        'building' => session('order_building', $user->profile->building),
    ]);

    $item->update(['sold' => true]);

    if (app()->environment('testing')) {
        return redirect('/success');
    }

    //Stripe
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $paymentMethodTypes = ($request->payment_method === 'コンビニ払い')
        ? ['konbini']
        : ['card'];

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => $paymentMethodTypes,
        'line_items' => [[
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $item->name,
                ],
                'unit_amount' => $item->price,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => url('/success'),
        'cancel_url' => url('/cancel'),
    ]);

    return redirect($session->url);
}

    //いいねの実装
    public function toggle($item_id)
    {
        $user=auth()->user();

        $is_favorited=$user->favorite()->where('item_id',$item_id)->exists();

        if($is_favorited){
            $user->favorite()->where('item_id',$item_id)->delete();
        }else{
            $user->favorite()->create([
                'item_id'=>$item_id,
            ]);
        }
        return back();
    }

}