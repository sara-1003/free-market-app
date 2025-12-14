<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要な情報が取得できる()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $this->actingAs($user);

        $user->profile()->create([
            'profile_img' => 'profile_images/test.png',
            'post_code'   => '123-4567',
            'address'     => '東京都',
            'building'    => 'テストビル',
        ]);

        $sellItem = Item::create([
            'user_id'     => $user->id,
            'name'        => '出品商品',
            'brand'       => 'ブランド',
            'description' => '説明',
            'price'       => 1000,
            'condition'   => '新品',
            'img'         => 'items/test.jpg',
            'sold'        => false,
        ]);

        $seller = User::factory()->create();

        $buyItem = Item::create([
            'user_id'     => $seller->id,
            'name'        => '購入商品',
            'brand'       => 'ブランド',
            'description' => '説明',
            'price'       => 2000,
            'condition'   => '新品',
            'img'         => 'items/test2.jpg',
            'sold'        => true,
        ]);

        Order::create([
            'user_id'        => $user->id,
            'item_id'        => $buyItem->id,
            'payment_method'=> 'カード払い',
            'post_code'     => '123-4567',
            'address'       => '東京都',
            'building'      => 'テストビル',
        ]);

        $response = $this->get('/mypage?page=sell');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('出品商品');

        $response = $this->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入商品');
    }

    public function test_変更項目が初期値として過去設定されていること()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $user->profile()->create([
            'profile_img' => 'profile_images/test.png',
            'post_code'   => '123-4567',
            'address'     => '東京都',
            'building'    => 'テストビル',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都');
        $response->assertSee('テストビル');
        $response->assertSee('profile_images/test.png');
    }
}
