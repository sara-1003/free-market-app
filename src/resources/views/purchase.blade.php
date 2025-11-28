@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
<div class="purchase__content">
    <form class="purchase-form" action="{{ url('/purchase/' . $item->id) }}" method="post">
    @csrf
    <div class="purchase__content--left">
        <div class="item__detail">
            <img src="{{ Str::startsWith($item->img, 'items/') ? asset('storage/' . $item->img) : asset($item->img) }}" >
            <div class="item__detail--group">
                <h2>{{ $item->name }}</h2>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
        </div>
        <div class="payment__item">
            <h3 class="payment__item-title">支払い方法</h3>
            <select class="payment__item-select" name="payment_method" id="payment_method">
                <option value="">選択してください</option>
                <option value="コンビニ払い" {{ old('payment_method') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                <option value="カード支払い" {{ old('payment_method') == 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
            </select>
            <p class="form__error">
            @error('payment_method')
            {{$message}}
            @enderror
        </div>
        <div class="address__item">
            <div class="address__item--option">
                <h3 class="address__item-title">配送先</h3>
                <a class="address__item--change" href="/purchase/address/{{$item->id}}">変更する</a>
            </div>
            <p class="address__item--post">〒{{ $profile->post_code }}</p>
            <p class="address__item--address">{{ $profile->address }}
                @if(!empty($profile->building))
                {{ $profile->building}}
                @endif
            </p>
            @error('address')
            {{$message}}
            @enderror
        </div>
    </div>
    <div class="purchase__content--right">
        <div class="payment-info__row">
            <span class="payment-info__label">商品代金</span>
            <span class="payment-info__value">¥{{ number_format($item->price) }}</span>
        </div>
        <div class="payment-info__row">
            <span class="payment-info__label">支払い方法</span>
            <span class="payment-info__value" id="payment-info-value">{{ old('payment_method','') }}</span>
        </div>
        <div class="purchase__button">
            <button class="purchase__button-submit" type="submit">購入する</button>
        </div>
    </div>
    </form>
</div>

<script>
const paymentSelect = document.getElementById('payment_method');
const paymentValue = document.getElementById('payment-info-value');

paymentSelect.addEventListener('change', function() {
    paymentValue.textContent = paymentSelect.value;
});
</script>

@endsection