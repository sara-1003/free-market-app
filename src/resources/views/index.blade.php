@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="tabs">
    <a class="tab__best {{ $tab === 'best' ? 'active' : '' }}" href="/?tab=best">おすすめ</a>
    @if(auth()->check())
        <a class="tab__mylist {{ $tab === 'mylist' ? 'active' : '' }}" href="/?tab=mylist">
            マイリスト
        </a>
    @else
        <a class="tab__mylist" href="{{ route('login') }}">マイリスト</a>
    @endif
</div>
<div class="items__list">
    @foreach($items as $item)
    <div class="item">
        <a class="item__link" href="/item/{{ $item->id }}">
            @if($item->sold)
            <div class="sold-label">SOLD</div>
            @endif
            <img src="{{ Str::startsWith($item->img, 'items/') ? asset('storage/' . $item->img) : asset($item->img) }}" >
            <p>{{ $item->name }}</p>
        </a>
    </div>
    @endforeach
</div>
@endsection