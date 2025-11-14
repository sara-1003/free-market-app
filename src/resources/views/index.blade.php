@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('header')
<div class="header__inner">
    <form class="search-form" action="/items/search" method="get">
        @csrf
        <input class="search-form__input" type="text" name="keyword" value="{{ old('keyword') }}" placeholder=" なにをお探しですか？">
    </form>
    <nav class="header-nav">
        <ul class="header-nav__list">
            <li class="header-nav__item">
                <form class="logout" action="/logout" method="post">
                    @csrf
                    <button class="logout__button" type="submit">ログアウト</button>
                </form>
            </li>
            <li class="header-nav__item">
                <a href="/mypage">マイページ</a>
            </li>
            <li class="header-nav__item">
                <a href="/sell">出品</a>
            </li>
        </ul>
    </nav>
</div>
@endsection

@section('content')
<div class="tabs">
    <a class="tab__best" href="/">おすすめ</a>
    <a class="tab__mylist" href="/?tab=mylist">マイリスト</a>
</div>
<div class="items__list">
    <div class="item">
        <a class="item__link" href="/item/{item_id}">
            <img src="" alt="商品画像">
            <p></p>
        </a>
    </div>
</div>
@endsection