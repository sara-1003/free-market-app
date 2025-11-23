@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_create.css')}}">
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
                <img src="{{ asset('storage/' . ($user->profile_img ?? 'profile_images/default.png')) }}">
            </div>
            <div class="profile-form__img--select">
                <label class="img__select--button">画像を選択する
                    <input type="file" name="profile_img" accept="image/*">
                </label>
                <p class="form__error">
                @error('profile_img')
                {{$message}}
                @enderror
            </p>
            </div>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="name">ユーザー名</label>
            <input class="profile-form__input" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
            <p class="form__error">
                @error('name')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="post_code">郵便番号</label>
            <input class="profile-form__input" type="text" name="post_code" id="post_code" maxlength="8" value="{{ old('post_code', $user->profile->post_code ?? '') }}">
            <p class="form__error">
                @error('post_code')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="address">住所</label>
            <input class="profile-form__input" type="text" name="address" id="address" value="{{ old('address', $user->profile->address ?? '') }}">
            <p class="form__error">
                @error('address')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label" for="building">建物名</label>
            <input class="profile-form__input" type="text" name="building" id="building" value="{{ old('building', $user->profile->building ?? '') }}">
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

<script>
    // プレビュー用のimg要素を取得
    const previewImg = document.querySelector('.profile-form__img--preview img');
    const fileInput = document.querySelector('input[name="profile_img"]');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(event) {
            previewImg.src = event.target.result; // 選択した画像をプレビュー
        }

        reader.readAsDataURL(file);
    });
</script>

@endsection