@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit_address.css')}}">
@endsection

@section('content')
<div class="edit_address--content">
    <div class="content__heading">
        <h2>住所の変更</h2>
    </div>
    <form action="/purchase/address/{{ $item->id }}" method="post">
    @csrf
    <input type="text" name="post_code" value="123-4567">
    <input type="text" name="address" value="東京都港区">
    <input type="text" name="building" value="サンプルマンション">
    <button type="submit">更新</button>
</form>
</div>
@endsection