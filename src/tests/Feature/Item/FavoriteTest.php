<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class favoriteTest extends TestCase
{
    public function test_いいねアイコンを押下することによって、いいねした商品として登録することができる。()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'description' => '説明文',
            'brand' => 'ブランド',
            'price' => 1000,
            'condition' => 'new',
            'img' => 'test.jpg',
        ]);

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $this->assertEquals(0, $item->favorite()->count());

        $response = $this->post("/favorite/{$item->id}");
        $response->assertStatus(302);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->refresh();

        $this->assertEquals(1, $item->favorite()->count());

        $response = $this->get("/item/{$item->id}");
        $response->assertSee("1");
    }

    public function test_追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('images/heart.png');
        $response->assertSee('0');

        $this->post("/favorite/{$item->id}")
            ->assertStatus(302);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->refresh();


        $this->assertEquals(1, $item->favorite->count());

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('images/heart-pink.png');
        $response->assertSee('1');
    }

    public function test_再度いいねアイコンを押下することによって、いいねを解除することができる()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'description' => '説明文',
            'price' => 1000,
            'condition' => '新品',
            'img' => 'items/test.jpg',
            'sold' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('images/heart.png');
        $response->assertSee('0');

        $this->post("/favorite/{$item->id}")
            ->assertStatus(302);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->refresh();
        $this->assertEquals(1, $item->favorite()->count());


        $this->post("/favorite/{$item->id}")
            ->assertStatus(302);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->refresh();
        $this->assertEquals(0, $item->favorite()->count());


        $response = $this->get("/item/{$item->id}");
        $response->assertSee('images/heart.png');
        $response->assertSee('0');
        }
}
