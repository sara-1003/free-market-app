@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="tabs">
    <a class="tab__best {{ auth()->check() ? '' : 'active' }}" href="/">おすすめ</a>
    <a class="tab__mylist {{ auth()->check() ? 'active' : '' }}" href="/?tab=mylist">マイリスト</a>
</div>
<div class="items__list">
    @foreach($items as $item)
    <div class="item">
        <a class="item__link" href="/item/{{ $item->id }}">
            <img src="{{ Str::startsWith($item->img, 'items/') ? asset('storage/' . $item->img) : asset($item->img) }}" >
            <p>{{ $item->name }}</p>
        </a>
    </div>
    @endforeach
</div>
@endsection