<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class searchTest extends TestCase
{
    use RefreshDatabase;

    public function test_「商品名」で部分一致検索ができる()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $itemA = Item::create([
            'name' => 'テスト商品A',
            'img' => 'items/a.jpg',
            'user_id' => $other->id,
            'description' => '説明A',
            'price' => 1000,
            'condition' => '新品',
            'sold' => false,
        ]);

        $itemB = Item::create([
            'name' => '商品B',
            'img' => 'items/b.jpg',
            'user_id' => $other->id,
            'description' => '説明B',
            'price' => 2000,
            'condition' => '新品',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get('/items/search?keyword=テスト');

        $response->assertStatus(200);

        $response->assertSee('テスト商品A');

        $this->assertStringNotContainsString('商品B', $response->getContent());
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $itemA = Item::create([
            'name' => 'テスト商品A',
            'img' => 'items/a.jpg',
            'user_id' => $other->id,
            'description' => '説明A',
            'price' => 1000,
            'condition' => '新品',
            'sold' => false,
        ]);

        $itemB = Item::create([
            'name' => '商品B',
            'img' => 'items/b.jpg',
            'user_id' => $other->id,
            'description' => '説明B',
            'price' => 2000,
            'condition' => '新品',
            'sold' => false,
        ]);

        $user->favoriteItems()->attach($itemA->id);

        $this->actingAs($user);

        $response = $this->get('/items/search?keyword=テスト');
        $response->assertStatus(200);

        $this->assertEquals('テスト', session('search_keyword'));

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertSee('テスト商品A');
        $this->assertStringNotContainsString('商品B', $response->getContent());
    }
}
