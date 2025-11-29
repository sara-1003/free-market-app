@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit_address.css')}}">
@endsection

@section('content')
<div class="edit_address--content">
    <div class="content__heading">
        <h2>住所の変更</h2>
    </div>
    <form  class="address-form" action="/purchase/address/{{$item->id}}" method="post">
        @csrf
        <div class="address-form__group">
            <label class="address-form__label">郵便番号</label>
            <input class="address-form__input" type="text" name="post_code" id="post_code" maxlength="8" value="{{ old('post_code', $user->profile->post_code ?? '') }}">
        </div>
        <p class="form__error">
            @error('post_code')
            {{$message}}
            @enderror
        </p>
        <div class="address-form__group">
            <label class="address-form__label" for="address">住所</label>
            <input class="address-form__input" type="text" name="address" id="address" value="{{ old('address', $user->profile->address ?? '') }}">
        </div>
        <p class="form__error">
            @error('address')
            {{$message}}
            @enderror
        </p>
        <div class="address-form__group">
            <label class="address-form__label" for="building">建物名</label>
            <input class="address-form__input" type="text" name="building" id="building" value="{{ old('building', $user->profile->building ?? '') }}">
        </div>
        <p class="form__error">
            @error('building')
            {{$message}}
            @enderror
        </p>
        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection