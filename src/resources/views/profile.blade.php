@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css')}}">
@endsection

@section('content')
<div class="profile__content">
    <div class="profile">
        <div class="profile__inner">
            <img class="profile__img" src="{{ asset('storage/' . ($user->profile->profile_img ?? 'profile_images/default.png')) }}">
            <h2 class="profile__name">{{$user->name}}</h2>
        </div>
        <div class="profile__edit">
            <a class="profile__edit--button" href="/mypage/profile">プロフィールを編集</a>
        </div>
    </div>
</div>
    <div class="tabs">
        <a class="tab__sell {{ $page === 'sell' ? 'active' : '' }}" href="/mypage?page=sell">出品した商品</a>
        <a class="tab__buy  {{ $page === 'buy' ? 'active' : '' }}" href="/mypage?page=buy">購入した商品</a>
    </div>
    <div class="items__list">
        @foreach($items as $item)
        <div class="item">
            <a class="item__link" href="/item/{{ $item->id }}">
                <img src="{{ asset('storage/' . $item->img) }}" alt="商品画像">
                <p>{{ $item->name }}</p>
            </a>
        </div>
        @endforeach
    </div>
@endsection