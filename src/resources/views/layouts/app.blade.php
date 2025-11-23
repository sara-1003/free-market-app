<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            {{-- ロゴは常に表示 --}}
            <a class="header__logo" href="/">
                <img src="{{ asset('images/header.png') }}" alt="COACHTECH">
            </a>

            {{-- 検索フォームの表示制御 --}}
            @if(!isset($hideSearch) || !$hideSearch)
                <form class="search-form" action="{{ route('items.search') }}" method="get">
                    <input class="search-form__input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                    <button type="submit">検索</button>
                </form>
            @endif

            {{-- ナビゲーション --}}
            @if(!isset($hideNav) || !$hideNav)
                <nav class="header-nav">
                    <ul class="header-nav__list">
                        @if(Auth::check())
                            {{-- ログイン後 --}}
                            <li class="header-nav__item">
                                <form class="logout" action="/logout" method="post">
                                    @csrf
                                    <button class="logout__button" type="submit">ログアウト</button>
                                </form>
                            </li>
                            <li class="header-nav__item"><a href="/mypage">マイページ</a></li>
                            <li class="header-nav__item"><a class="header__sell--button" href="/sell">出品</a></li>
                        @else
                            {{-- ログイン前 --}}
                            <li class="header-nav__item"><a href="/login">ログイン</a></li>
                            <li class="header-nav__item"><a href="/register">マイページ</a></li>
                            <li class="header-nav__item"><a class="header__sell--button" href="/sell">出品</a></li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
