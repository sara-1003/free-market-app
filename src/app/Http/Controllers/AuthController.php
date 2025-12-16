<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;



class AuthController extends Controller
{
public function index(Request $request)
{
    $tab = $request->tab;
    $keyword = session('search_keyword');

    if (auth()->check() && $tab === null) {
        $tab = 'mylist';
    }

    if ($tab !== 'mylist' && $tab !== null) {
        session()->forget('search_keyword');
        $keyword = null;
    }

    if ($tab === 'mylist') {
        if (!auth()->check()) {
            $items = collect();
        } else {
            $query = auth()->user()->favoriteItems();

            if (!empty($keyword)) {
                $query->where('name', 'like', "%{$keyword}%");
        }

            $items = $query->latest()->get();
        }
    } else {
        $tab = 'best';
        $query = Item::query();

        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $items = $query->latest()->get();
    }

    return view('index', compact('items', 'tab'));
}

public function search(Request $request)
{
    $keyword = $request->keyword;

    if (!empty($keyword)) {
        session(['search_keyword' => $keyword]);
    }

    $query = Item::query();

    if (auth()->check()) {
        $query->where('user_id', '!=', auth()->id());
    }

    if (!empty($keyword)) {
        $query->where('name', 'like', "%{$keyword}%");
    }

    $items = $query->latest()->get();
    $tab = 'best';

    return view('index', compact('items', 'tab'));
}
}