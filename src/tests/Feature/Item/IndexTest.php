<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class indexTest extends TestCase
{

    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        $user = User::factory()->create();

        Item::create([
            'name' => 'テスト商品1',
            'img' => 'items/test1.jpg',
            'user_id' =>  $user->id,
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'sold' => false,
        ]);

        Item::create([
            'name' => 'テスト商品2',
            'img' => 'items/test2.jpg',
            'user_id' =>  $user->id,
            'description' => '説明文',
            'price' => 2000,
            'condition' => '新品',
            'sold' => false,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('テスト商品1');
        $response->assertSee('テスト商品2');

        $response->assertSee('items/test1.jpg');
        $response->assertSee('items/test2.jpg');
    }

    public function test_購入済み商品は「Sold」と表示される()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'name' => 'テスト商品',
            'img' => 'items/test.jpg',
            'user_id' => $user->id,
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('SOLD');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user=User::factory()->create();
        $otherUser = User::factory()->create();

        Item::create([
            'name' => '自分の商品',
            'img' => 'items/myitem.jpg',
            'user_id' => $user->id,
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'sold' => false,
        ]);

        Item::create([
            'name' => '他人の商品',
            'img' => 'items/other.jpg',
            'user_id' => $otherUser->id,
            'description' => '説明文',
            'price' => 2000,
            'condition' => '新品',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=best');

        $response->assertDontSee('自分の商品');

        $response->assertSee('他人の商品');
    }

    public function test_いいねした商品だけが表示される()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $likedItem = Item::create([
            'name' => 'いいねした商品',
            'img' => 'items/liked.jpg',
            'user_id' => $otherUser->id,
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'sold' => false,
        ]);

        $notLikedItem = Item::create([
            'name' => 'いいねしていない商品',
            'img' => 'items/notliked.jpg',
            'user_id' => $otherUser->id,
            'description' => '説明文',
            'price' => 2000,
            'condition' => '新品',
            'sold' => false,
        ]);

        $user->favorite()->create([
            'item_id' => $likedItem->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('いいねした商品');

        $response->assertDontSee('いいねしていない商品');
    }

    public function test_マイリストにて購入済み商品は「Sold」と表示される()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $soldItem = Item::create([
            'name' => '購入済み商品',
            'img' => 'items/sold.jpg',
            'user_id' => $seller->id,
            'description' => '説明文',
            'price' => 2000,
            'condition' => '新品',
            'sold' => true,
        ]);

        $user->favorite()->create([
            'item_id' => $soldItem->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('購入済み商品');

        $response->assertSee('SOLD');
    }

    public function test_未認証の場合は何も表示されない()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) {
            return $items->count() === 0;
        });

        $response->assertDontSee('SOLD');
    }
}
