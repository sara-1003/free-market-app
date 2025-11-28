@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
@endsection

@section('content')
<div class="detail__content">
    <div class="detail__content--left">
        <div class="detail__img">
            <img src="{{ Str::startsWith($item->img, 'items/') ? asset('storage/' . $item->img) : asset($item->img) }}" >
        </div>
    </div>
    <div class="detail__content--right">
        <h2 class="item__name">{{ $item->name }}</h2>
        <p class="item__brand">{{ $item->brand }}</p>
        <p class="item__price">¥{{ number_format($item->price) }} <span>(税込)</span></p>
        <div class="item__detail--icons">
            <div class="item__favorite">
                <img src="{{ $favorited ? asset('images/heart-pink.png') : asset('images/heart.png') }}">
                <span>{{ $favoriteCount }}</span>
            </div>
            <div class="item__comment">
                <img src="{{ asset('images/comment.png') }}">
                <span>{{ $commentCount }}</span>
            </div>
        </div>
        <div class="buy__button">
            <a class="buy__button--link" href="/purchase/{{ $item->id }}">購入手続きへ</a>
        </div>
        <div class="item__description">
            <h3 class="item__description-title">商品説明</h3>
            <p>{{ $item->description }}</p>
        </div>
        <div class="item__information">
            <h3 class="item__information-title">商品の情報</h3>
            <div class="item__category">
                <p class="item__category-title">カテゴリー</p>
                @foreach($categories as $category)
                    <span class="item__category-span">{{ $category->name }}</span>
                @endforeach
            </div>
            <div class="item__condition">
                <p class="item__condition-title">商品の状態</p>
                <span class="item__condition-span">{{ $item->condition }}</span>
            </div>
        </div>
        <div class="item__detail--comment">
            <h3 class="item__comment-title">コメント({{ $comments->count() }})</h3>
        </div>
        @foreach($comments as $comment)
        <div class="comment__item">
            <div class="comment__user">
                <img src="{{ $comment->user->profile && $comment->user->profile->profile_img
                    ? asset('storage/' . $comment->user->profile->profile_img)
                    : asset('img/default_user.png') }}" alt="user icon">
                <span>{{ $comment->user->name }}</span>
            </div>
            <div class="comment__text">
                {{ $comment->comment }}
            </div>
        </div>
        @endforeach
        <form class="comment-form" action="/item/{{ $item->id }}/comment" method="post">
            @csrf
            <h4>商品へのコメント</h4>
            <textarea name="comment"></textarea>
            <p class="form__error">
                @error('comment')
                {{$message}}
                @enderror
            <button type="submit">コメントを送信する</button>
        </form>
    </div>
</div>
@endsection