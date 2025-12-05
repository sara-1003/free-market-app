@extends('layouts.app')

@php
$hideSearch = true;
$hideNav    = true;
@endphp

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/email.css')}}">
@endsection

@section('content')
<div class="email__content">
    <p class="email__text">登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>
    <a class="btn" href="http://localhost:8025" target="_blank">認証はこちらから</a>
    <form class="form" action="{{ route('verification.send') }}" method="post">
        @csrf
        <button class="form__button" type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection