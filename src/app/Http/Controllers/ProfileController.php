<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Profile;


class ProfileController extends Controller
{
    //プロフィール設定画面
    public function create()
    {
        $user=Auth()->user();

        return view('profile_create',compact('user'));
    }

    //プロフィール設定の保存処理(新規)
    public function store(ProfileRequest $request)
    {
        $user=Auth::user();

        //profileテーブルにnameがないのでuserテーブルに保存
        $user->update([
            'name' => $request->input('name'),
        ]);

        $profile=$request->only([
            'post_code',
            'address',
            'building',
        ]);

        // 画像保存処理
        if ($request->hasFile('profile_img')) {
            $filename = $request->file('profile_img')->store('profile_images', 'public');
            $profile['profile_img'] = $filename;
        } else {
            $profile['profile_img'] = 'profile_images/default.png';
        }

        $user->profile()->create($profile);
        
        return redirect('/');
    }

    //プロフィール画面の表示
    public function index(Request $request)
    {
        $user=auth()->user();
        
        $page=$request->query('page','sell');

        if($page === 'sell'){
            $items=$user->item()->get();
        }else{
            $items=$user->orders()->with('item')->get()->pluck('item');
        }
        return view('profile',compact('user','items','page'));
    }

    // プロフィール編集画面の表示
    public function edit()
    {
        $user=Auth::user();
        return view('edit',compact('user'));
    }

    //プロフィール編集画面の更新処理
    public function update(ProfileRequest $request)
    {
        $user=Auth::user();

        //profileテーブルにnameがないのでuserテーブルに保存
        $user->update([
            'name' => $request->input('name'),
        ]);

        $profile=$request->only([
            'post_code',
            'address',
            'building',
        ]);

        // 画像がアップロードされた場合のみ更新
        if ($request->hasFile('profile_img')) {
            $profile['profile_img'] = $request->file('profile_img')->store('profile_images', 'public');
        }

        $user->profile()->update($profile);
        
        return redirect('/mypage');
    }
}
