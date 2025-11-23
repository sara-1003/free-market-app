@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('content')
<div class="sell__content">
    <div class="sell__heading">
        <h1>商品の出品</h1>
    </div>
    <form class="sell-form" action="/sell" method="post" enctype="multipart/form-data">
        @csrf
        <div class="item__img">
            <h3 class="detail__title">商品画像</h3>
            <div class="item__img-box">
                <label class="item__img-label" for="img">画像を選択する</label>
                <input class="item__img-input" type="file" name="img" id="img" accept="image/*">
                <img class="item__img-preview" id="preview" src="" alt="">
            </div>
            <p class="form__error">
                @error('img')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="item_detail">
            <div class="detail__heading">
                <h2>商品の詳細</h2>
            </div>
            <div class="item__category">
                <h3 class="detail__title">カテゴリー</h3>
                <div class="item__category-list">
                    @foreach($categories as $category)
                        <label class="item__category-label">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : '' }}>
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="form__error">
                @error('category_ids')
                {{$message}}
                @enderror
                </p>
            </div>
            <div class="item__condition">
                <h3 class="detail__title">商品の状態</h3>
                <select class="item__condition--select" name="condition" id="condition">
                    <option value="">選択してください</option>
                    <option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>良好</option>
                    <option value="目立った傷や汚れなし"{{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり"{{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="状態が悪い"{{ old('condition') == '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                <p class="form__error">
                @error('condition')
                {{$message}}
                @enderror
            </p>
            </div>
        </div>
        <div class="item__explanation">
            <div class="detail__heading">
                <h2>商品名と説明</h2>
            </div>
            <div class="item__name">
                <h3 class="detail__title">商品名</h3>
                <input class="item__name--input" type="text" name="name" value="{{ old('name') }}">
                <p class="form__error">
                @error('name')
                {{$message}}
                @enderror
            </p>
            </div>
            <div class="item__brand">
                <h3 class="detail__title">ブランド名</h3>
                <input class="item__brand--input" type="text" name="brand" value="{{ old('brand') }}">
            </div>
            <div class="item__description">
                <h3 class="detail__title">商品の説明</h3>
                <textarea class="item__description--textarea" name="description">{{ old('description') }}</textarea>
                <p class="form__error">
                @error('description')
                {{$message}}
                @enderror
            </p>
            </div>
            <div class="item__price">
                <h3 class="detail__title">販売価格</h3>
                <div class="price__wrapper">
                    <span class="item__price--span">¥</span>
                    <input class="item__price--input" type="number" name="price" value="{{ old('price') }}">
                </div>
            <p class="form__error">
                @error('price')
                {{$message}}
                @enderror
            </p>

            </div>
        </div>
        <div class="sell__button">
            <button class="sell__button-submit" type="submit">出品する</button>
        </div>
    </form>
</div>

<script>
// 画像プレビュー
document.getElementById('img').addEventListener('change', function(e){
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    if(file){
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});

// カテゴリ選択ラベル全体に色を付ける
const categoryLabels = document.querySelectorAll('.item__category-label');

categoryLabels.forEach(label => {
    const input = label.querySelector('input[type="checkbox"]');
    
    // ページロード時にチェックされているものは selected クラスを付与
    if (input.checked) {
        label.classList.add('selected');
    }

    // チェックが変更された時の処理
    input.addEventListener('change', () => {
        if(input.checked){
            label.classList.add('selected');
        } else {
            label.classList.remove('selected');
        }
    });
});
</script>
@endsection