@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_create.css')}}">
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
<div class="profile__content">
    <div class="profile__heading">
        <h2>プロフィール設定</h2>
    </div>
    <form class="profile-form" action="/profile" method="post" enctype="multipart/form-data">
        @csrf
        <div class="profile-form__img">
            <div class="profile-form__img--preview">
                <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="プロフィール画像">
            </div>
            <div class="profile-form__img--select">
                <label class="img--select--button">画像を選択する
                    <input type="file" name="profile_img" accept="image/*">
                </label>
            </div>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="name">ユーザー名</label>
            <input class="profile-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
            <p class="form__error">
                @error('name')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="post_code">郵便番号</label>
            <input class="profile-form__input" type="text" name="post_code" id="post_code" maxlength="8"
            pattern="[0-9]{3}-[0-9]{4}" value="{{ old('post_code') }}">
            <p class="form__error">
                @error('post_code')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="address">住所</label>
            <input class="profile-form__input" type="text" name="address" id="address" value="{{ old('address') }}">
            <p class="form__error">
                @error('address')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="building">建物名</label>
            <input class="profile-form__input" type="text" name="building" id="building" value="{{ old('building') }}">
            <p class="form__error">
                @error('building')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection